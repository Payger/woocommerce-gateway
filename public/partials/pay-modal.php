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

if ( empty( $session_data ) ) {
	return;
}

$order            = new WC_Order( $order_id );
$name             = $order->get_billing_first_name() . ' ' . $order->get_billing_first_name();
$email            = $order->get_billing_email();
$items            = $order->get_items();
$cart_items       = Woocommerce_Payger_Gateway::get_cart_items_names( $order );
$site_name        = get_bloginfo( 'name' );
$currency         = $session_data['currency'];
$rate             = $session_data['rate'];
$precision        = $session_data['precision'];
$description      = __( 'Payment for: ', 'payger' ) . $cart_items;
$selling_currency = get_option('woocommerce_currency');
$amount           = $order->get_total();

//we already have this data so we are not creating a new payment
if ( $order->get_meta('payger_qrcode', true ) ) {

	$qrCode       = $order->get_meta( 'payger_qrcode', true );
	$address      = $order->get_meta( 'payger_address', true );
	$input_amount = $order->get_meta( 'payger_ammount', true );

} else {
	$args   = array(
		'externalId'        => sprintf( '%03d', $order_id ),
		'description'       => $description,
		'inputCurrency'     => $currency,
		'outputCurrency'    => $selling_currency,
		'source'            => $site_name,
		'outputAmount'      => $amount,
		'buyerName'         => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
		'buyerEmailAddress' => $order->get_billing_email(),
		'callback'          => array( 'url' => WC()->api_request_url( 'WC_Gateway_Payger' ), 'method' => 'POST' ),
		'ipAddress'         => $_SERVER['REMOTE_ADDR'],
		'metadata'          => array('meta1'=>'value1'),
	);

	$response = Payger::post( 'merchants/payments/', $args );

	$res = Woocommerce_Payger_Gateway::handle_payment_response( $response, $order_id, $currency );

	if ( ! $res ) {
		return; //exit modal if payment api call fails
	}
	$qrcode_image = $res['image'];
	$input_amount = $res['amount'];
	$qrCode       = $res['code'];
	$address      = $res['address'];
}

//Get applicable fee
$fee      = '';
$args     = array(
	'inputCurrency'  => $currency,
	'outputAmount'   => $amount,
	'outputCurrency' => $selling_currency
);
print_r( $args);
$response = Payger::post( 'merchants/fees/', $args );
$success  = ( 200 === $response['status'] ) ? true : false; //bad response if status different from 201
if( $success )
{
	$fee = $response['data']->content->fee;
	$fee = round( $fee, $precision );
}

error_log('FEES RESPONSE');
error_log( print_r( $response, true ) );

$html  = '<input type="hidden" class="order_id" value="' . $order_id . '">';
$html .= '<p><a id="modal" href="#ex1" rel="modal:open" class="hide">'. __('Open Modal', 'payger') .'</a></p>';

$html .= '<div id="ex1" class="modal">';
$html .= '<div class="content">';
$html .= '<div class="top-header">';
$html .= '<div class="header">';
$html .= '<div class="header__icon">';
$html .= '<img class="header__icon__img" src="' . plugin_dir_url( __FILE__ ) . '../assets/images/logo.png">';
$html .= '</div>';
$html .= '</div>';
$html .= '<div class="timer-row">';
//$html .= '<div class="timer-row__progress-bar" style="width: 4.16533%;"></div>';
$html .= '<div class="timer-row__spinner">';
$html .= '<bp-spinner>';
$html .= '<svg xml:space="preserve" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 50 50" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px">';
$html .= '<path d="M11.1,29.6c-0.5-1.5-0.8-3-0.8-4.6c0-8.1,6.6-14.7,14.7-14.7S39.7,16.9,39.7,25c0,1.6-0.3,3.2-0.8,4.6l6.1,2c0.7-2.1,1.1-4.3,1.1-6.6c0-11.7-9.5-21.2-21.2-21.2S3.8,13.3,3.8,25c0,2.3,0.4,4.5,1.1,6.6L11.1,29.6z"></path>';
$html .= '</svg>';
$html .= '</bp-spinner>';
$html .= '</div>';
$html .= '<div class="timer-row__message">';
$html .= '<span>';
$html .= '<span i18n="">' . __( 'Awaiting Payment...', 'payger' ) . '</span>';
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
$html .= $description;
$html .= '</div>';
$html .= '</div>';
$html .= '</div>';
$html .= '	<div class="single-item-order__row selected-currency">
					<div class="single-item-order--left">
						<div class="single-item-order--left__currency">
							' . $currency . '
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
						<div class="line-items__item__value">' . $input_amount - $fee . ' '. $currency .'</div>
					</div>
					<div class="line-items__item">
						<div class="line-items__item__label">
							<span i18n="">' . __('Network Cost', 'payger' ) . '</span>
						</div>
						<div class="line-items__item__value">'. $fee . ' ' . $currency . '</div>
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
									<span class="item-highlighter item-highlighter--large item-highlighter--primary" i18n="">' . __( 'Copy payment URL', 'payger' ) . '</span>
									<img src="' . plugin_dir_url( __FILE__ ) . '../assets/images/copy-icon.svg">
								</div>
							</div>
						</manual-box>
					</div>
					<div class="manual__step-one__instructions manual__step-one__instructions--how-to-pay">
						<a class="item-highlighter item-highlighter--large item-highlighter--secondary" href="https://payger.com/" target="_blank">
							<span i18n=""> ' . __('How do I pay this?', 'payger' ) . '</span>
						</a>
					</div>

				</div>

			</div>


		</div>'; //.payment-box
$html .= '</div>'; //.content
$html .= '</div>';