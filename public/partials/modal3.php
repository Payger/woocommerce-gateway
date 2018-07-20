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

$session_data = WC()->session->get( 'crypto_meta' );

if( empty( $session_data ) ) {
	return;
}


$cart_items = array();
if( ! WC()->cart->is_empty() ) {
	$items = WC()->cart->get_cart();
	foreach ( $items as $item => $values ) {
		$_product     =  wc_get_product( $values['data']->get_id());
		$cart_items[] = $_product->get_title();
	}
	$cart_items = implode( ',', $cart_items );
}

$currency = $session_data['currency'];
$rate     = $session_data['rate'];
$amount   = $session_data['amount'];

$selling_currency = get_option('woocommerce_currency');

$error_message = false;
$args = array (
	'asset'      => $currency,
	'amount'     => $amount,
	'externalId' => $order_id,
	'callback'   => array( 'url' => WC()->api_request_url( 'WC_Gateway_Payger' ), 'method' => 'POST' ),
);
$response = Payger::post( 'merchants/payments/', $args );

error_log('MODAL REQUEST QRCODE');
error_log( print_r( $response, true));

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

	$qrcode_img = $uploads['baseurl'] . $filename;

	//save meta to possible queries and to show information on thank you page or emails
	$order->add_meta_data( 'payger_currency', $asset );
	$order->add_meta_data( 'payger_ammount', $amount );
	$order->add_meta_data( 'payger_qrcode', $qrCode );
	$order->add_meta_data( 'payger_qrcode_image', $qrcode_img ); //stores qrcode url so that email can use this.
	$order->add_meta_data( 'payger_payment_id', $payment_id );
	$order->add_meta_data( 'payger_address', $address );

	// Mark as on-hold (we're awaiting the cheque)
	$order->update_status( 'pending', __( 'Awaiting Payger payment', 'payger' ) );
	$order->add_order_note( __( 'DEBUG PAYMENT ID ' . $payment_id, 'payger' ) );

	//do not reduce stock levels at this point, payment is not set

	$order->save();

	// Remove cart
	$woocommerce->cart->empty_cart();

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
$html .= '<div class="timer-row__time-left">14:22</div>';
$html .= '</div>'; //.timer-row
$html .= '</div>'; //.top-header
$html .= '<div class="order-details">';
$html .= '<div class="single-item-order">';
$html .= '<div class="single-item-order__row">';
$html .= '<div class="single-item-order--left">';
$html .= '<div class="single-item-order--left__name">';
$html .=  get_bloginfo( 'name' );
$html .= '</div>';
$html .= '<div class="single-item-order--left__description">';
$html .= __( 'Payment for: ', 'payger' ) . $cart_items;
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
						<div class="line-items__item__value">' . $amount . ' '. $currency .'</div>
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
						<div class="line-items__item__value">' . $amount . ' '. $currency .'</div>
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
									<span class="item-highlighter item-highlighter--large item-highlighter--primary" i18n="">Copy payment URL</span>
									<img src="assets/images/copy-icon.svg">
								</div>
								<div class="copy-item--hide">
									<span class="item-highlighter item-highlighter--large item-highlighter--primary" i18n="">Copied</span>
									<img src="assets/images/check-blue.svg">
								</div>
							</div>
						</manual-box>
					</div>
					<div class="manual__step-one__instructions manual__step-one__instructions--how-to-pay">
						<a class="item-highlighter item-highlighter--large item-highlighter--secondary" href="https://support.bitpay.com/hc/en-us/articles/115005559826" target="_blank">
							<span i18n="">How do I pay this?</span>
						</a>
					</div>

				</div>

			</div>


		</div>'; //.payment-box
$html .= '</div>'; //.content
$html .= '</div>';