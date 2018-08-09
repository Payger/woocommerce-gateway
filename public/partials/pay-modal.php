<?php
/**
 *
 * Payment Modal with time tracking
 *
 */
?>

<?php

?>
<!-- Link to open the modal -->



<?php

if( ! $order_id ) {
	return; //we need order id to have proper date on modal
}

global $woocommerce;
$session_data = WC()->session->get( 'crypto_meta' );

if( empty( $session_data ) ) {
	error_log('EMPTY SESSION DATA');
	return;
}


$order = new WC_Order( $order_id );

$name  = $order->get_billing_first_name() . ' ' . $order->get_billing_first_name();
$email = $order->get_billing_email();
$items = $order->get_items();

// Get list of items to buy to have a proper description
$cart_items = array();
if( ! empty( $items ) ) {
	foreach ( $items as $item ) {
		$cart_items[] = $item->get_name();
	}
}
$cart_items = implode( ',', $cart_items );

//Collect data for request
$site_name        = get_bloginfo( 'name' );
$currency         = $session_data['currency'];
$rate             = $session_data['rate'];
$amount           = $order->get_total();
$description      = __( 'Payment for: ', 'payger' ) . $cart_items;
$selling_currency = get_option('woocommerce_currency');


$success   = false;

//we already have this data so we are not creating a new payment
if ( $order->get_meta('payger_qrcode', true ) ) {
	$qrCode       = $order->get_meta( 'payger_qrcode', true );
	$address      = $order->get_meta( 'payger_address', true );
	$input_amount = $order->get_meta( 'payger_ammount', true );
} else {
	$new_order = true;
	$args      = array(
		'externalId'        => $order_id . time(),
		'description'       => $description,
		'inputCurrency'     => $currency,
		'outputCurrency'    => $selling_currency,
		'source'            => $site_name,
		'outputAmount'      => $amount,
		'buyerName'         => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
		'buyerEmailAddress' => $order->get_billing_email(),
		'callback'          => array( 'url' => WC()->api_request_url( 'WC_Gateway_Payger' ), 'method' => 'POST' ),
	);

	$order->add_order_note( __( 'DEBUG CALLBACK ' . WC()->api_request_url( 'WC_Gateway_Payger' ), 'payger' ) );

	$response = Payger::post( 'merchants/payments/', $args );
	$success  = ( 201 === $response['status'] ) ? true : false; //bad response if status different from 201


	if ( $success ) {

		$payment = $response['data']->content->subPayments;
		$payment = $payment[0];

		$payment_id = $payment->id;
		$qrCode     = $payment->qrCode;
		$address    = $payment->address;

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

		$qrcode_img = $uploads['baseurl'] . $filename;

		$input_amount = $payment->inputAmount;

		//save meta to possible queries and to show information on thank you page or emails
		$order->add_meta_data( 'payger_currency', $asset );
		$order->add_meta_data( 'payger_ammount', $input_amount );
		$order->add_meta_data( 'payger_qrcode', $qrCode );
		$order->add_meta_data( 'payger_qrcode_image', $qrcode_img ); //stores qrcode url so that email can use this.
		$order->add_meta_data( 'payger_payment_id', $payment_id );
		$order->add_meta_data( 'payger_address', $address );

		// Mark as on-hold (we're awaiting the cheque)
		//$order->update_status( 'on-hold', __( 'Awaiting Payger payment', 'payger' ) );
		$order->add_order_note( __( 'DEBUG PAYMENT ID ' . $payment_id, 'payger' ) );

		wc_reduce_stock_levels( $order_id );

		$order->save();

		// Remove cart
		$woocommerce->cart->empty_cart();


		//schedule event to check this payment status
		wp_schedule_event( time(), 'minute', 'payger_check_payment', array(
			'payment_id' => $payment_id,
			'order_id'   => $order_id
		) );
	} else {
		$error_message = $response['data']->error->message;
		$error_message = apply_filters( 'payger_payment_error_message', $error_message );
		wc_add_notice( __('Payment error: ', 'payger') . $error_message, 'error' );
		error_log('ERROR MESSAGE '.$error_message);
	//	wp_safe_redirect( $order->get_checkout_payment_url( true ) );
	}
}

$html  = '<input type="hidden" class="order_id" value="' . $order_id . '">';
$html .= '<p><a id="modal" href="#ex1" rel="modal:open" class="hide">Open Modal</a></p>';

$html .= '<div id="ex1" class="modal">';
$html .= '<div class="content">';
$html .= '<div class="top-header">';
$html .= '<div class="header">';
$html .= '<div class="header__icon">';
$html .= '<img class="header__icon__img" src="' . plugin_dir_url( __FILE__ ) . '../assets/images/logo.png">';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="timer-row">';
$html .= '<div class="timer-row__progress-bar" style="width: 4.16533%;"></div>';
$html .= '<div class="timer-row__spinner">';
$html .= '<bp-spinner>';
$html .= '<svg xml:space="preserve" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 50 50" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px">';
$html .= '<path d="M11.1,29.6c-0.5-1.5-0.8-3-0.8-4.6c0-8.1,6.6-14.7,14.7-14.7S39.7,16.9,39.7,25c0,1.6-0.3,3.2-0.8,4.6l6.1,2c0.7-2.1,1.1-4.3,1.1-6.6c0-11.7-9.5-21.2-21.2-21.2S3.8,13.3,3.8,25c0,2.3,0.4,4.5,1.1,6.6L11.1,29.6z"></path>';
$html .= '</svg>';
$html .= '</bp-spinner>';
$html .= '</div>';
$html .= '<div class="timer-row__message">';
$html .= '<span>';
$html .= '<span i18n="">Awaiting Payment...</span>';
$html .= '</span>';
$html .= '</div>';
$html .= '<div class="timer-row__message error hide">';
$html .= '<span>';
$html .= '<span i18n="">' . __( 'This payment has expired', 'payger') . '</span>';
$html .= '</span>';
$html .= '</div>';
$html .= '<div class="timer-row__time-left">15:00</div>';
$html .= '</div>'; //.timer-row
$html .= '</div>'; //.top-header
$html .= '<div class="order-details">';
$html .= '<div class="single-item-order">';
$html .= '<div class="single-item-order__row">';
$html .= '<div class="single-item-order--left">';
$html .= '<div class="single-item-order--left__name">';
$html .=  $site_name;
$html .= '</div>';
$html .= '<div class="single-item-order--left__description">';
$html .= __( 'Payment for: ', 'payger' ) . $description;
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '	<div class="single-item-order__row selected-currency">
					<div class="single-item-order--left">
						<div class="single-item-order--left__currency">
							<img src="assets/images/currency_logos/btc.svg">
							Bitcoin
						</div>
					</div>

					<div class="single-item-order--right">
						<rate class="ex-rate">
							<div>
								1 ' . $selling_currency . ' = ' . $rate .' ' . $currency . '
							</div>
						</rate>
					</div>
				</div>';

$html .= '</div>'; //.single-item-order;
$html .= '<line-items class="expanded">
				<div class="line-items">
				<div>
					<div class="line-items__item">
						<div class="line-items__item__label" i18n="">' . __('Payment Amount', 'payger') . '</div>
						<div class="line-items__item__value">' . $input_amount . ' '. $currency .'</div>
					</div>
					<div class="line-items__item">
						<div class="line-items__item__label">
							<span i18n="">Network Cost</span>
							<a href="https://help.bitpay.com/paying-with-bitcoin/why-am-i-being-charged-an-additional-network-cost-on-my-bitpay-invoice" target="_blank">';
$html .= '<img src="' . plugin_dir_url( __FILE__ ) . '../assets/images/invoice-fee-question.svg ">';
$html .=				'</a>
						</div>
						<div class="line-items__item__value">0.000007 '. $currency . '</div>
					</div>
					<div class="line-items__item line-items__item--total">
						<div class="line-items__item__label" i18n="">' . __('Total', 'payger') . '</div>
						<div class="line-items__item__value">' . $input_amount . ' '. $currency .'</div>
					</div>
				</div>
				</div>
			</line-items>';
$html .= '</div>'; //.order-details
$html .= '<div class="payment-box">

			<div class="bp-view payment scan" id="scan">
				<div class="payment__scan">
					<div class="qr-codes hidden-xs-down qr-code-container fade-in-up">
						<qr-code class="payment__scan__qrcode"><img src="data:image/gif;base64,' . $qrCode->content . '" width="220" height="220"></qr-code>
						<manual-box>
							<div ngxclipboard="">
								<div class="copy-item">
									<input id="address" type="hidden" value="' . $address . ' ">
									<span class="item-highlighter item-highlighter--large item-highlighter--primary" i18n="">Copy payment URL</span>
									<img src="' . plugin_dir_url( __FILE__ ) . '../assets/images/copy-icon.svg">
								</div>
							</div>
						</manual-box>
					</div>
					<div class="manual__step-one__instructions manual__step-one__instructions--how-to-pay">
						<a class="item-highlighter item-highlighter--large item-highlighter--secondary" href="https://payger.com/" target="_blank">
							<span i18n="">How do I pay this?</span>
						</a>
					</div>

				</div>

			</div>


		</div>'; //.payment-box
$html .= '</div>'; //.content
$html .= '</div>';