<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.widgilabs.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Gateway_Payger
 * @subpackage Woocommerce_Gateway_Payger/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Gateway_Payger
 * @subpackage Woocommerce_Gateway_Payger/admin
 * @author     WidgiLabs <contact@widgilabs.com>
 */
class Woocommerce_Payger_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * Payger Instance
	 */
	private $payger;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-gateway-payger-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-gateway-payger-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Tell Woocommerce we have a new payment gateway
	 *
	 * @param    $methods
	 * @return   array
	 * @since    1.0.0
	 * @author   Ana Aires ( ana@widgilabs.com )
	 */
	public function add_payger_gateway_class( $methods ) {

		$methods[] = 'Woocommerce_Payger_Gateway';

		return $methods;

	}

	public function init_gateway( ) {

		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			return;
		}

		require_once  plugin_dir_path(__DIR__ ) . '/includes/class-woocommerce-payger-gateway.php';

		$this->payger = new Woocommerce_Payger_Gateway( );

	}


	/** Add every minute interval ro recurrence array
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function payger_intervals( $schedules ) {
		$schedules['minute'] = array(
			'interval' => 60,
			'display'  => __('Every Minute', 'payger'),
		);
		return $schedules;

	}

	/**
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function process_admin_options() {

		if ( ! isset( $_POST['woocommerce_payger_gateway_key'] ) || empty( $_POST['woocommerce_payger_gateway_key'] ) ) {

			WC_Admin_Settings::add_error( __( 'Error: You must enter API key.', 'payger' ) );
			unset($_POST['woocommerce_payger_gateway_enabled']);
			return false;
		}

		if ( ! isset( $_POST['woocommerce_payger_gateway_secret'] ) || empty( $_POST['woocommerce_payger_gateway_secret'] ) ) {

			WC_Admin_Settings::add_error( __( 'Error: You must enter API secret.', 'payger' ) );
			unset($_POST['woocommerce_payger_gateway_enabled']);
			return false;
		}

		$key    = $_POST['woocommerce_payger_gateway_key'];
		$secret = $_POST['woocommerce_payger_gateway_secret'];

		Payger::setUsername( $key );
		Payger::setPassword( $secret );

		if( ! $token = Payger::connect( true ) ) {
			WC_Admin_Settings::add_error( __( 'Error: Your api credentials are not valid. Please double check that you entered them correctly and try again.', 'payger' ) );
			unset($_POST['woocommerce_payger_gateway_enabled']);
			update_option( 'payger_token', '' );
			return false;
		}

		update_option( 'payger_token', $token );

		return true;
	}

	/**
	 * Given a cryptocurrency get it's exchange rates
	 *
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function get_quote() {

		if ( ! isset( $_GET['to'] ) ) {

			$error_message = apply_filters( 'payger_no_currency_error_message', __('You must choose a cryptocurrency first.', 'payger' ) );
			wc_add_notice( __('Payment error: ', 'payger') . $error_message, 'error' );
			wp_send_json_error();
		}

		$choosen_crypto = $_GET['to'];
		$key            = isset( $_GET['order_key'] ) ? $_GET['order_key'] : false;
		$order_id       = isset( $_GET['order_id'] ) ? $_GET['order_id'] : false;

		//$data = $this->payger->get_quote( $choosen_crypto, $key, $order_id  );
		$data = $this->payger->get_quote( $choosen_crypto, $key, $order_id  );

		if( is_array( $data ) ) {
			wp_send_json_success( $data );
		} else {
			wp_send_json_error();
		}
	}


	/***
	 * When order changes status to on-hold and user is notified info
	 * to make the payment (qrCode) is added to the email
	 * @param $order
	 * @param $sent_to_admin
	 * @param $plain_text
	 *
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function update_email_instructions( $order, $sent_to_admin, $plain_text ) {

		if ( $sent_to_admin ) {
			return; //admin gets the email no need to give him payment details
		}

		$payment_method = $order->get_payment_method();

		if ( 'payger_gateway' !== $payment_method ) {
			return; //we only want to proceed if this is an order payed with payger
		}

		if( ! $order->has_status( 'on-hold' ) )
		{
			return; //order not on-hold
		}

		$qrCode  = $order->get_meta( 'payger_qrcode_image' );
		$address = $order->get_meta('payger_address');
		$currency = $order->get_meta('payger_currency');
		$amount   = $order->get_meta('payger_ammount');

		$message = apply_filters( 'payger_thankyou_previous_qrCode', __('Please use the following qrCode to process your payment.', 'payger') );

		if( $qrCode ) {

			printf( '<p>%2$s</p>
					<div class="qrcode">
						<span>%3$s</span>
					</div>
					 <p><img src="%1$s" alt="%8$s"></p>
					  <p>%6$s %4$s %7$s %5$s </p>',
				$qrCode,              //1
				esc_html( $message ), //2
				esc_html( $address ), //3
				esc_html($amount), //4
				esc_html($currency), //5
				esc_html__('You will pay', 'payger'),//6
				esc_html__('in', 'payger'), //7
				esc_attr__('Payger qrCode', 'payger') //8
			);
		}

	}

	/**
	 * Listens to Payger Callback
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function check_payger_response() {

		//id must be set with the payment identifier
		if ( ! isset( $_POST['id'] ) ) {

			wp_die( 'Payger IPN Request Failure', 'Payger IPN', array( 'response' => 500 ) );
		}

		$payment_id = $_POST['id'];

		//perform request check status
		//$payger_instance  = $this->payger->get_instance();
		//$response         = $payger_instance->get( 'merchants/payments/' . $payment_id );
		$response           = Payger::get( 'merchants/payments/' . $payment_id );

		$result = $response['data']->content;
		
		if ( is_object( $result ) ) {

			$order_id = $result->externalId; // external id was set with order id when payment was issued
			$order    = new WC_Order( $order_id );

			if ( 'PAID' === $result->status ) {

				//get order with this payment id

				// update order status to 'processing' payment was confirmed
				$order->update_status( 'processing', __( 'Payger Payment Confirmed', 'payger' ) );

			} else {

				$order->add_order_note( __('Still Waiting for Payment', 'payger' ) );

				//TODO Update Payment
				//send buyer new email

				//check if there was any payment

				//if partially paid then update payment and get new qRCode
			}
		}
	}

	/**
	 * This checks for payment status and update order accordingly
	 * @param $payment_id
	 * @param $order_id
	 *
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function check_payment( $payment_id, $order_id ) {

		error_log('I AM CHECKING PAYMENT TRIGGERD BY CRON');

		$response = Payger::get( 'merchants/payments/' . $payment_id );
		$status   = $response['data']->content->status;
		$order    = new WC_Order( $order_id );

		error_log('PAYMENT STATUS ' .$status );

		switch( $status ) {
			case 'PENDING' : break; //do nothing order still waits for payment
			case 'PAID' :
				if ( 'processing' !== $order->get_status() ) {
					//change status
					$order->update_status( 'processing', __( 'Payger Payment Confirmed', 'payger' ) );
					$order->add_order_note( __( 'Payment is verified and completed.', 'payger' ) );
					//TODO Delete cron job
				}

				break;
			case 'UNDERPAID' : break;
			case 'OVERPAID' : break;
		}

	}

	/*
	 *
	 */
	public function cancel_order( $order_id ) {

		$order          = new WC_Order( $order_id );
		$payment_method = $order->get_payment_method();

		//not our gateway so lets ignore
		if ( 'payger_gateway' !== $payment_method ) {
			return; //we only want to proceed if this is an order payed with payger
		}

		$this->payger->cancel_payment( $order_id );

	}
}
