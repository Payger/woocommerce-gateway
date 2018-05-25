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


	/**
	 * Given a cryptocurrency get it's exchange rates
	 *
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function get_quote() {

		if ( ! isset( $_GET['to'] ) ) {
			wp_send_json_error();
		}

		$selling_currency = get_option('woocommerce_currency');
		$choosen_crypto   = $_GET['to'];
		$amount           = WC()->cart->cart_contents_total;

		$payger_instance  = $this->payger->get_instance();
		$response         = $payger_instance->get( 'merchants/exchange-rates', array('from' => $selling_currency, 'to'=> $choosen_crypto, 'amount' => $amount ) );

		$result = $response['data']->content->rates;
		$result = $result[0]; //I am interested in a single quote
		$rate   = $result->rate;
		$amount = $result->amount;

		wp_send_json_success( array('rate' => $rate, 'amount'=> $amount ) );

	}

}
