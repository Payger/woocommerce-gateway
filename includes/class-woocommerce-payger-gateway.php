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

		$this->id           = 'payger_gateway';
		$this->icon         = 'https://payger.com/wp-content/uploads/2018/03/logo_green-350x75.png';
		$this->has_fields   = true;
		$this->method_title = __( 'Payger', 'payger' );
		$this->title        = __( 'Payger', 'payger' );
		$this->description  = __( 'Pay with cryptocurrency (powered by Payger)', 'payger' );


		$key    = $this->get_option( 'key' );
		$secret = $this->get_option( 'secret' );
		$token  = get_option( 'payger_token', '' );

		Payger::setUsername( $key );
		Payger::setPassword( $secret );
		Payger::setToken( $token );

		$token = Payger::connect();
		//this will save new token if needed.
		if( $token ) {
			update_option( 'payger_token', $token );
		}


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
	 * Return the gateway's icon.
	 *
	 * @return string
	 */
	public function get_icon() {

		$icon = $this->icon ? '<img src="' . WC_HTTPS::force_https_url( $this->icon ) . '" alt="' . esc_attr( $this->get_title() ) . '" />' : '';

		$icon .= sprintf( '<a href="%1$s" class="about_paypal" target="_blank">' . esc_attr__( 'What is Payger?', 'payger' ) . '</a>', esc_url( 'http://www.payger.com' ) );


		return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
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
	 * Makes payger gateway available only if there is currencies selected
	 * on settings page.
	 * @return bool
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function is_available() {
		$currencies = $this->get_option( 'accepted' );
		if ( ! $currencies || empty( $currencies ) ) {
			return false;
		}

		return parent::is_available();
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

	/**
	 * Gets current woocommerce currency and checks which are the corresponding currencies this
	 * merchant can offer as payment possible currencies.
	 * exchange-rates should filter results based on from currency
	 *
	 * @return array
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function get_accepted_currencies_options() {

		$selling_currency = get_option('woocommerce_currency');

		$args = array('from' => $selling_currency, 'amount' => 10 ); //we need to pass an amoun it's a bridge requirement
		//error_log(print_r($args, true));
		//$response = Payger::get( 'merchants/exchange-rates', $args );

		$response = Payger::get( 'merchants/currencies' );


		//error_log('GET ACCEPTED CURRENCIES RESPONSE ');
		//error_log( print_r( $response, true ) );

		$currencies = array();

		if ( 200 !== $response['status'] ) {
			return $currencies;
		}

		if ( $rates = $response['data']->content->currencies ) {
			foreach ( $rates as $currency ) {
				$currencies[ $currency->name ] = ucfirst( $currency->longName );
			}
		}
		update_option('payger_possible_currencies', $currencies );

		return $currencies;
	}

	/**
	 * Form to output on checkout
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function payment_fields() {

		$description = $this->description;

		if ( $description ) {
			echo wpautop( wptexturize( trim( $description ) ) );
		}

		$selling_currency = get_option('woocommerce_currency');
		$currency_options = $this->get_option( 'accepted' );
		$options          = '';

		$possible_currencies = get_option('payger_possible_currencies', true );
		if ( $currency_options && ! empty( $currency_options ) ) {
			foreach ( $currency_options as $option ) {

				$options .= sprintf( '<option value="%1$s">%2$s</option>',
					$option,
					$possible_currencies[$option] );
			}
		}

		$order_id = get_query_var( 'order-pay' ) ? absint( get_query_var( 'order-pay' ) ) : 0;

		if( ! empty( $options ) ) {
			printf(
				'<p class="form-row form-row-wide">
					<label for="<?php echo $this->id; ?>">%1$s
						<abbr class="required" title="required">*</abbr>
					</label>
					<select name="%2$s" id="%2$s_coin">
					<option value="0">%4$s</option>
						%3$s
					</select>
					<input type="hidden" class="order_id" value="%11$s">
					<div id="payger_convertion" class="hide">%5$s <span class="payger_amount"></span> %6$s <span class="payger_rate"></span> = 1 %7$s</div>
				</p>
				<div class="hide" id="dialog" title="Payger Confirmation">
  					<p>%8$s <span class="update_amount"></span> %9$s <span class="update_rate"></span> = 1 %7$s %10$s</p>
				</div>',
				__( 'Choose Currency', 'payger' ),
				$this->id,
				$options,
				__( 'Please choose one...' , 'payger' ),
				__('You will pay', 'payger'),
				__('at rate', 'payger'),
				esc_html( $selling_currency ),
				__( 'Your currency rate was recently updated. You will pay a total amount of', 'payger' ),
				__( 'corresponding to a rate of', 'payger' ),
				__( 'Please confirm you want to proceed with your order.', 'payger' ),
				$order_id
			);
		}

		require_once plugin_dir_path( __FILE__ ) . '/../public/partials/modal.php';

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

		error_log('INIT PROCESS PAYMNET ');

		global $woocommerce;
		$error_message = false;
		$order         = new WC_Order( $order_id );

		$amount   = WC()->cart->cart_contents_total;
		$asset    = $_POST['payger_gateway'];

		//get session meta
		$session_data = WC()->session->get( 'crypto_meta' );
		if ( ! empty( $session_data ) ) {
			$amount = $session_data['amount'];
			$limits = $session_data['limit'];

			if ( $amount > $limits ) {
				$limits        = false;
				$error_message = apply_filters( 'payger_enforce_limits', __( 'Your order amount exceeds the allowed limit for ' . $asset . '. Please choose other currency or review your order.', 'payger' ) );
			}
		}

		//check for currency limits
		$args = array (
			'asset'      => $asset,
			'amount'     => $amount,
			'externalId' => $order_id,
			'callback'   => array( 'url' => WC()->api_request_url( 'WC_Gateway_Payger' ), 'method' => 'POST' ),
		);

		$order->add_order_note( __('DEBUG CALLBACK '.WC()->api_request_url( 'WC_Gateway_Payger' ), 'payger' ) );

		//$response = $this->payger->post( 'merchants/payments/', $args );
		$response = Payger::post( 'merchants/payments/', $args );

		error_log('--------------------------------------------> PROCESS PAYMENT');
		error_log(print_r($response, true));

		$success = ( 201 === $response['status'] ) ? true : false; //bad response if status different from 201

		if ( $success && ! $error_message ) {

			$qrCode     = $response['data']->content->qrCode;
			$payment_id = $response['data']->content->id;
			$address    = $response['data']->content->address;

			//
			$data        = base64_decode( $qrCode->content );
			$uploads     = wp_upload_dir();
			$upload_path = $uploads['basedir'];
			$filename    = '/payger_tmp/' . $order_id . '.png';

			// create temporary directory if does not exists
			if ( ! file_exists( $upload_path . '/payger_tmp' ) ) {
				mkdir( $upload_path . '/payger_tmp' );
			}

			//always update file so that if qrcode changes for this
			//payment the code is still valid
			file_put_contents( $upload_path . $filename, $data );

			//save meta to possible queries and to show information on thank you page or emails
			$order->add_meta_data( 'payger_currency', $asset );
			$order->add_meta_data( 'payger_ammount', $amount );
			$order->add_meta_data( 'payger_qrcode', $qrCode );
			$order->add_meta_data( 'payger_qrcode_image', $uploads['baseurl'] . $filename ); //stores qrcode url so that email can use this.
			$order->add_meta_data( 'payger_payment_id', $payment_id );
			$order->add_meta_data( 'payger_address', $address );

			// Mark as on-hold (we're awaiting the cheque)
			$order->update_status( 'pending', __( 'Awaiting Payger payment', 'payger' ) );
			$order->add_order_note( __( 'DEBUG PAYMENT ID ' . $payment_id, 'payger' ) );

			//do not reduce stock levels at this point, payment is not set

			$order->save();

			// Remove cart
			$woocommerce->cart->empty_cart();

			// Return thankyou redirect
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order )
			);
		} else {

			//check if error message was previously set
			if ( ! $error_message ) {
				$error_message = $response['data']->error->message;
				$error_message = apply_filters( 'payger_payment_error_message', $error_message );
			}
			wc_add_notice( __('Payment error: ', 'payger') . $error_message, 'error' );
			return;
		}
	}

	/**
	 * Given a cryptocurrency get it's exchange rates
	 *
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function get_quote( $choosen_crypto, $order_key = false, $order_id = false ) {

		$selling_currency = get_option('woocommerce_currency');
		$amount           = $this->get_order_total();


		if ( $order_key && ! $order_id && 0 == $amount ) {
			$order_id =  wc_get_order_id_by_order_key( $order_key );
		}

		if( $order_id && 0 == $amount ){
			$order   = new WC_Order( $order_id );
			$amount  = $order->get_total();
		}

		error_log('GET QUOTE');
		$response = Payger::get( 'merchants/exchange-rates', array('from' => $selling_currency, 'to'=> $choosen_crypto, 'amount' => $amount ) );
		error_log(print_r($response, true));

		$success = ( 200 === $response['status'] ) ? true : false; //bad response if status different from 200

		if ( $success ) {

			$result    = $response['data']->content->rates;
			$result    = $result[0]; //I am interested in a single quote
			$limit     = $result->limit;
			$precision = $result->precision;
			$rate      = round( $result->rate, $precision );
			$amount    = round( $result->amount, $precision );

			// will store meta info so that we can use it later
			// to process payment
			WC()->session->set( 'crypto_meta', array(
				'currency'  => $choosen_crypto,
				'rate'      => $rate,
				'amount'    => $amount,
				'limit'     => $limit,
				'precision' => $precision //maybe needed but we are already setting the correct precision
			) );
			return array( 'rate' => $rate, 'amount' => $amount );
		} else {
			$error_message = $response['data']->error->message;
			$error_message = apply_filters( 'payger_get_quote_error_message', $error_message );
			wc_add_notice( __('Payment error: ', 'payger') . $error_message, 'error' );
			return;
		}

	}

	/**
	 * Given order id and cancel payment
	 * @param $order_id
	 *
	 * @since 1.0.0
	 * @author Ana Aires ( ana@widgilabs.com )
	 */
	public function cancel_payment( $order_id ) {

		$order = new WC_Order( $order_id );

		$payment_id = $order->get_meta('payger_payment_id', true);

		//$this->payger->delete( 'merchants/payments/' . $payment_id, array() );
		Payger::delete( 'merchants/payments/' . $payment_id, array() );
	}

}