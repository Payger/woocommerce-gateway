<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.widgilabs.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Gateway_Payger
 * @subpackage Woocommerce_Gateway_Payger/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Gateway_Payger
 * @subpackage Woocommerce_Gateway_Payger/public
 * @author     WidgiLabs <contact@widgilabs.com>
 */
class Woocommerce_Payger_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-gateway-payger-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//these are scripts we only need on checkout so enqueue them only on checkout page
		if( is_checkout() ) {

			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-gateway-payger-public.js', array( 'jquery', 'jquery-ui-dialog' ), $this->version, true );

			//localize script
			$ajax_nonce = wp_create_nonce( "payger" );
			$vars_array = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'	 	=> $ajax_nonce,
			);

			wp_localize_script( $this->plugin_name, 'payger', $vars_array );

		}
	}

	/**
	 * Given an order id shows qrCode on thank you page so that
	 * users can perform payment.
	 * This runs only when Payger was the choosen payment method
	 * @param $order_id
	 *
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function update_thank_you( $order_id ) {

		$order   = new WC_Order( $order_id );
		$qrCode  = $order->get_meta('payger_qrcode');
		$address = $order->get_meta('payger_address');
		$currency = $order->get_meta('payger_currency');
		$amount   = $order->get_meta('payger_ammount');

		//FIXME possible add currency icon

		$message = apply_filters( 'payger_thankyou_previous_qrCode', __('Please use the following qrCode to process your payment.', 'payger') );

		if ( $qrCode ) {
			printf( '
					 <p>%3$s</p>
					 <div class="qrcode">
						<input type="text" id="qrCode_text" value="%5$s"> <button class="copy_clipboard">%4$s</button>
					</div>
					 <p><img src="data:image/%2$s;base64,%1$s" alt="Payger qrCode"></p>
					 <p>%8$s %6$s %9$s %7$s </p>',
				$qrCode->content, //1
				$qrCode->fileType, //2
				esc_html( $message ), //3
				esc_html__( 'Copy', 'payger' ), //4
				esc_attr( $address ), //5
				esc_html($amount), //6
				esc_html($currency), //7
				esc_html__('You will pay', 'payger'),//8
				esc_html__('in', 'payger') //9
			);
		}
	}

}
