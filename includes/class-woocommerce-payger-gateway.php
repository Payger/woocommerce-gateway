<?php

/**
 * The file that defines the core gateway class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.widgilabs.com
 * @since      1.0.0
 *
 * @package    Payger
 * @subpackage Payger/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Payger
 * @subpackage Payger/includes
 * @author     Ana Aires <ana@widgilabs.com>
 */
class Woocommerce_Payger_Gateway extends WC_Payment_Gateway {

	/*
	 * Payger instance so we can process requests
	*/
	protected $payger = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( )
	{
		require_once( 'Payger.php' );



		$this->id                 = 'payger_gateway';
		$this->icon               = 'https://payger.com/wp-content/themes/payger/images/logo.png';
		$this->has_fields         = true;
		$this->method_title       = 'Payger';
		$this->method_description = __( 'Pay with bitcoins brought to you by Payger', 'payger' );


		$key    = $this->get_option( 'key' );
		$secret = $this->get_option( 'secret' );

		$payger = new Payger();
		$payger->setPassword( $secret );
		$payger->setUsername( $key );
		$payger->connect();

		$this->payger = $payger;


		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->title       = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );

		//This will save our settings
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
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

		$methods[] = 'WC_Payger_Gateway';

		return $methods;

	}

	/**
	 * Process this plugin admin options for payger gateway
	 *
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled'     => array(
				'title'   => __( 'Enable/Disable', 'payger' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable payments through Payger', 'payger' ),
				'default' => 'yes'
			),
			'title'       => array(
				'title'       => __( 'Title', 'payger' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'payger' ),
				'default'     => __( 'Payger', 'payger' ),
				'desc_tip'    => true,
			),
			'description' => array(
				'title'       => __( 'Description', 'payger' ),
				'type'        => 'text',
				'default'     => __( 'Pay with cryptocurrency provided by Payger', 'payger' ),
				'description' => __( 'This controls the description which the user sees during checkout.', 'payger' ),
				'desc_tip'    => true,
			),
			'key'         => array(
				'title'       => __( 'Username', 'payger' ),
				'type'        => 'text',
				'description' => __( 'Key provided by Payger when signing the contract.', 'payger' ),
				'desc_tip'    => true,
			),
			'secret'      => array(
				'title'       => __( 'Password', 'payger' ),
				'type'        => 'password',
				'description' => __( 'Secret provided by Payger when signing the contract.', 'payger' ),
				'desc_tip'    => true,
			),
			'advanced' => array(
				'title'       => __( 'Advanced options', 'payger' ),
				'type'        => 'title',
				'description' => '',
			),
			'accepted' => array(
				'title'       => __( 'Accepted Currencies', 'payger' ),
				'type'        => 'multiselect',
				'class'       => 'wc-enhanced-select',
				'description' => __( 'Choose which are the currencies you will allow users to pay with. This depends on your shop currency choosen on Woocommerce General Options ', 'payger' ),
				'default'     => 'bitcoin',
				'desc_tip'    => true,
				'options'     => $this->get_accepted_currencies_options(),
			),
		);
	}


	public function get_accepted_currencies_options() {


		$selling_currency = get_option('woocommerce_currency');

		$response = $this->payger->get( 'merchants/exchange-rates', array('from' => $selling_currency ) );

		error_log('RESPONSE ');


		$rates = $response['data']->content->rates;

		$currencies = array();
		foreach( $rates as $rate ) {

			$currencies[ $rate->asset ] = $rate->asset;
		}

		return $currencies;
	}

	/**
	 * Form to output on checkout
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function payment_fields() {

		$description = $this->get_description();

		/*if ( 'yes' == $this->sandbox ) {
			$description .= ' ' . sprintf( __( 'TEST MODE ENABLED. Use a test card: %s', 'woocommerce' ), '<a href="https://www.simplify.com/commerce/docs/tutorial/index#testing">https://www.simplify.com/commerce/docs/tutorial/index#testing</a>' );
		}*/

		if ( $description ) {
			echo wpautop( wptexturize( trim( $description ) ) );
		}

		?>
		<p class="form-row form-row-wide">
			<br/>
			<label for="<?php echo $this->id; ?>">
				<?php _e( 'Choose Currency', 'payger' ); ?>
				<abbr class="required" title="<?php _e( 'required', 'payger' ); ?>">*</abbr>
			</label>
			<input type="text" autocomplete="off" class="input-text" name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" required/>
		</p>
	<?php
	}

	/**
	 * Actual function for payment process
	 * @param int $order_id
	 *
	 * @return array
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function process_payment( $order_id ) {
		global $woocommerce;
		$order = new WC_Order( $order_id );

		// Mark as on-hold (we're awaiting the cheque)
		$order->update_status('on-hold', __( 'Awaiting Payger payment', 'payger' ));


		error_log( 'Process Payment with Payger' );

		$request = new PaygerRequest( 'sequences.get' );
		$request->process_request();

		//request payger


		// Reduce stock levels
		$order->reduce_order_stock();

		// Remove cart
		$woocommerce->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order )
		);
	}

}