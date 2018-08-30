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

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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
	 * Given a crypto currency get it's exchange rates
	 * This is used by the ajax call
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function get_quote() {

		if ( ! isset( $_GET['to'] ) ) {
			$error_message = apply_filters( 'payger_no_currency_error_message', __('You must choose a crypto currency first.', 'payger' ) );
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

		$qrCode   = $order->get_meta( 'payger_qrcode_image', true );
		$address  = $order->get_meta( 'payger_address', true );
		$currency = $order->get_meta( 'payger_currency', true );
		$amount   = $order->get_meta( 'payger_ammount', true );

		$message = apply_filters( 'payger_thankyou_previous_qrCode', __('Please use the following qrCode to process your payment.', 'payger') );

		if( $qrCode ) {

			printf( '<p>%2$s</p>
					<div class="qrcode">
						<span>%3$s</span>
					</div>
					 <p><img src="%1$s" alt="%8$s"></p>
					  <p>%6$s %4$s %7$s %5$s </p>', //You will pay 0,00054 in BTC
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
	 * This method is triggered by the cron event
	 * @param $payment_id
	 * @param $order_id
	 *
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function check_payment( $payment_id, $order_id ) {

		if( ! $payment_id ) {
			return;
		}

		$response = Payger::get( 'merchants/payments/' . $payment_id );

		$status   = $response['data']->content->status;
		$order    = new WC_Order( $order_id );
		$total    = $order->get_total();

		$input_currency  = $order->get_meta( 'payger_currency' );
		$output_currency = get_option('woocommerce_currency');

		error_log('PAYMENT STATUS FOR ORDER ' .$order_id . ' ' .$status );

		switch( $status ) {
			case 'PENDING' :
				break; //do nothing order still waits for payment
			case 'PAID' :
				error_log('PAID ');
				if ( 'processing' !== $order->get_status() ) {
					//change status
					$order->update_status( 'processing', __( 'Payger Payment Confirmed', 'payger' ) );
					$order->add_order_note( __( 'Payment is verified and completed.', 'payger' ) );
				}

				//clear hook for this payment
				wp_clear_scheduled_hook( 'payger_check_payment', array( 'payment_id' => $payment_id, 'order_id' => $order_id ) );

				break;
			case 'UNDERPAID' :
				error_log('UNDERPAID ');

				//check for missing amount

				$subpayments = $response['data']->content->subPayments;
				$paid        = 0;
				foreach( $subpayments as $payment ) {
					if ( 'transaction_seen' == $payment->status ) {
						$paid = $paid + $payment->actualOutputAmount;
					}
				}

				$missing_value = $total - $paid;

				// update order not stating there is missing amount and new email was sent
				$order->add_order_note( __( 'Payment is verified but not completed. Missing amount of ', 'payger' ) . $missing_value . $output_currency . __( ' an email was sent to the buyer.', 'payger' ) );

				$args = array(
					'inputCurrency'  => $input_currency,
				    'outputAmount'   => $missing_value,
                    'outputCurrency' => $output_currency
				);

				// trigger payment update
				$response = Payger::post( 'merchants/payments/' . $payment_id . '/address', $args );

				//202 successfully updated
				$success = ( 202 === $response['status'] ) ? true : false; //bad response if status different from 201

				if ( $success ) {

					$subpayments = $response['data']->content->subPayments;


					//we need to check the pending subpayment, this
					//will be the one with the data for the missing
					//payment
					foreach( $subpayments as $subpayment ) {
						if ( 'pending' == $subpayment->status ) {
							$payment = $subpayment;
							break;
						}
					}

					//  error_log(print_r($payment, true));
					$qrCode  = $payment->qrCode;
					$address = $payment->address;

					//Build qrCode image
					$qrcode_image = $this->payger->generate_qrcode_image( $order_id, $payment );

					//update store values for qrcode
					$order->update_meta_data( 'payger_ammount', $payment->inputAmount );
					$order->update_meta_data( 'payger_qrcode', $qrCode );
					$order->update_meta_data( 'payger_qrcode_image', $qrcode_image ); //stores qrcode url so that email can use this.
					$order->update_meta_data( 'payger_address', $address );

					$order->save_meta_data();

					// trigger new email
					$this->trigger_email( $order_id, 'customer_underpaid_order' );
				}
				break;
			case 'OVERPAID' :
				error_log('OVERPAID');
				if ( 'processing' !== $order->get_status() ) {

					//calculate overpaid amount
					$subpayments = $response['data']->content->subPayments;
					$paid = 0;
					foreach( $subpayments as $payment ) {
						if ('transaction_seen' == $payment->status ) {
							$paid = $paid + $payment->actualOutputAmount;
						}
					}
					$overpaid = $paid - $total;

					//change status
					error_log('CHANGING THE STATUS FOR OVERPAID ORDER THIS SHOULD TRIGGEr THE EMAI');
					$order->update_status( 'processing', __( 'Payger Payment Confirmed', 'payger' ) );

					$order->add_order_note( __( 'Payment is verified and completed. The amount of ', 'payger' ) . $overpaid . $output_currency . __(' was overpaid.', 'payger' ) );
				}

				//clear hook
				wp_clear_scheduled_hook( 'payger_check_payment', array( 'payment_id' => $payment_id, 'order_id' => $order_id ) );
				break;

			case 'EXPIRED' :
				error_log('EXPIRED');

				$order_status = $order->get_status();
				if ( 'cancelled' == $order_status ) {
					wp_clear_scheduled_hook( 'payger_check_payment', array( 'payment_id' => $payment_id, 'order_id' => $order_id ) );
					break; //order was already canceled do nothing
				}

				$max = $this->payger->get_option( 'max_expired' );

				$expired = $order->get_meta( 'payger_expired', true );
				if ( $max == $expired ) {
					$order->update_status( 'cancelled', __( 'Payger Payment Expired '. $max . ' times', 'payger' ) );
					//clear hook
					wp_clear_scheduled_hook( 'payger_check_payment', array( 'payment_id' => $payment_id, 'order_id' => $order_id ) );
					break;
				}

				$expired = $expired + 1;


				$args = array(
					'inputCurrency' => $input_currency,
					'outputAmount' => $total,
					'outputCurrency' => $output_currency
				);

				// trigger payment update
				$response = Payger::post( 'merchants/payments/' . $payment_id . '/address', $args );

				//get new qrcode
				//202 successfully updated
				$success = ( 202 === $response['status'] ) ? true : false; //bad response if status different from 201

				if ( $success ) {

					//we need to check the pending subpayment, this
					//will be the one with the data for the missing
					//payment
					$subpayments = $response['data']->content->subPayments;
					foreach( $subpayments as $subpayment ) {
						if ( 'pending' == $subpayment->status ) {
							$payment = $subpayment;
							break;
						}
					}
					$qrCode   = $payment->qrCode;
					$address  = $payment->address;

					//Build qrCode image
					$qrcode_image = $this->payger->generate_qrcode_image( $order_id, $payment );


					//update store values for qrcode
					$order->update_meta_data( 'payger_ammount', $payment->inputAmount );
					$order->update_meta_data( 'payger_qrcode', $qrCode );
					$order->update_meta_data( 'payger_qrcode_image', $qrcode_image ); //stores qrcode url so that email can use this.
					$order->update_meta_data( 'payger_address', $address );
					$order->update_meta_data( 'payger_expired', $expired );

					$order->save_meta_data();

					//update store values for qrcode

					//trigger new email
					WC()->mailer()->emails['WC_Email_Customer_On_Hold_Order']->trigger( $order_id, $order );

					// update order not stating first address for payment expired
					$expired_message = $expired - 1;
					$order->add_order_note( __( 'Address # ' . $expired_message . ' for payment expired, new one sent to email ', 'payger' ) );
				}

				break;

			case 'FAILED' :

				//change status
				$order->update_status( 'failed', __( 'Payger Payment Failed', 'payger' ) );

				//clear hook
				wp_clear_scheduled_hook( 'payger_check_payment', array( 'payment_id' => $payment_id, 'order_id' => $order_id ) );
				break;
		}

	}

	/*
	 * Call cancel payment for a previous payment that was canceled
	 * This is hooked on the woocommerce_cancelled_order
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

	/**
	 * This method serves ajax requests for cancel order
	 * after timeout
	 * */
	public function cancel_expired_order() {

		if ( ! isset( $_GET['order_id'] ) ) {
			wp_send_json_error();
			return;
		}

		$order_id = $_GET['order_id'];
		$order    = new WC_Order( $order_id );
		$order->update_status( 'cancelled', __( 'Unpaid order cancelled - time limit reached.', 'payger' ) );

		$this->payger->cancel_payment( $order_id );

		wp_send_json_success();
	}


	/**
	 * Send email according status
	 * @param $order_id
	 * @param $email_id
	 *
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */

	public function trigger_email( $order_id, $email_id ) {
		$mailer = WC()->mailer();
		$mails = $mailer->get_emails();

		error_log( print_r( $mails, true ) );

		foreach ( $mails as $mail ) {
			if ( $mail->id == $email_id ) {
				$mail->trigger( $order_id );
			}
		}
	}

	/**
	 * This method serves ajax requests for finding order status
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function check_order_status() {

		if ( ! isset( $_GET['order_id'] ) ) {
			wp_send_json_error();
			return;
		}

		$order_id = $_GET['order_id'];
		$order    = new WC_Order( $order_id );
		$data     = array( 'status' => $order->get_status(), 'thank_you_url' => $this->payger->get_return_url( $order ) );

		wp_send_json_success( $data );
	}
}
