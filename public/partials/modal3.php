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


$order = new WC_Order( $order_id );

$name =  $order->get_billing_first_name() . ' ' . $order->get_billing_first_name();
$email = $order->get_billing_email();

// Get list of items to buy
$cart_items = array();
if( ! WC()->cart->is_empty() ) {
	$items = WC()->cart->get_cart();
	foreach ( $items as $item => $values ) {
		$_product     =  wc_get_product( $values['data']->get_id());
		$cart_items[] = $_product->get_title();
	}
	$cart_items = implode( ',', $cart_items );
}

$site_name   = get_bloginfo( 'name' );
$currency    = $session_data['currency'];
$rate        = $session_data['rate'];
$amount      = $order->get_total(); //$session_data['amount'];
$description = __( 'Payment for: ', 'payger' ) . $cart_items;


$selling_currency = get_option('woocommerce_currency');

$error_message = false;
$args          = array (
	'outputCurrency'     => $selling_currency,
	'inputCurrency'    => $currency,
	'externalId'        => $order_id,
	'description'       => $description,
	'outputAmount'      => $amount,
	'callback'          => array( 'URL' => WC()->api_request_url( 'WC_Gateway_Payger' ), 'method' => 'POST' ),
	'source'            => $site_name,
	'buyerName'         => $name,
	'buyerEmailAddress' => $email
);

error_log(print_r($args, true));

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
$html .=  $site_name;
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
						<qr-code class="payment__scan__qrcode"><img src="data:image/gif;base64,' . 'R0lGODdh3QHdAYAAACI6Vf///ywAAAAA3QHdAQAC/4SPqcvtHKKcVLaKs970LY55oqFd2YiAaoQeq2q+MtiSc1nn+s730By7CTu1my91+oWKw0qu6VRCh8+p5YjNan1GqRXIlGlxn2Trywpbg+hX9buNy+deWL1NU6/GZgUZ1fa2doe3IThFl6jIJ0ZY2DfShfWXQCkSqAfF9miY2bQIGnpoV8aZdwY2CYm0hArnSeVoOjH6KXqLK7vqMstR65tlydsKiPYrtNlL5DqY65wr6acs/BDNtWtDHGkMi6zbe2z9PK4oPjwdxbwXjA1A7YCprvk9G55KfoTebp7ttsjvrp29RvKuFASmShurdNIIFnNYSR/DbRJp0VvW0F+5e/8RFfKI93DiJY493pkk6QFgRYwUVwZIhjDjuo0QF7Isye3gy27vjsHcWUpjyJkyKw6sd9FiUKJ0VArkibLa06Wk8u27WjNl1IAugQ41mtRg0VOJnHrcAbLlTa1Z0WIVeY7pyLZcXR419fMkXUZCO8J1+0rnXaVWz9Zd208u2742weqclvet4jhm/+pIO9dyZMM+HW3WHDYNVYmDH31GfLhq0609oe717Bq0YNijY6pV3Zh0bLyhvfqdvKUy6tK2Mw/vzRe1XsaLgXcV/VXsNcPLnUsmjC938bjYjc9LaLl1bd/ayXOXXr56949Tx0KfHv462d/rE9fHJ/z+adzsqbf/p4/eee+Vt1+A9hl42X8EsuNfg8wdOKCA5mWXH4IFbgdYfJzR5t6EqSFYYYQZHjdecvepN9+CJWbXIYoiwpNTYRrK1uFPNiLHIXwk1sjgjMqxJt+L5ITo4YWdgPfjhisuyeN4LhbZTWdInhjkkU1eySKEVgIoZG/iRffil1x6+KSRN05pYY44OZjkgx+GqeAtRBK35Zo+6qemhGe2WKWJaTKpI5Vs8qflnlm+WaeeuxGa4KB/YqkipBKW2eOOY/oJp6MYImpolnMummJ/dz56aZ6F4gionZZGGiiIcWoJEKbeOPkqopSi2eVjMbaa6ai5ggllqqK2ueqkW8lKpJjp//VZKZ66BiYjsYLSeBuprKo6bbGwHtusLbQqyaemwQKL7K7YuiqurFJGm62z4bpZLl2xoirpiO5WW4i6pnLa7a+l4kpmrZjJMfC24I55K7vWNseJvsKeCvC6vAZ88LKvAZyswEBWzPDCUoHTL8W+josvydcO267HtnKL8cbw0vuvwv56Z1rInSoacZQ272uksih7+662K6e7c8vQTnwzxDKbjPPSScN49BwFD02txSM7vPTUEp+sNNI65/zwodAwGzYoPhs8csIl90y201+7XbbYoqjNdShnUy1tqF3T3CsiPL+9dtH1yi1n2zGPzTHaeW9695Ofgk2u4IcTjvjVf//TVLWx4tLdcd/fDb5150x/bDnlz3C+d+GJ450y4xpv7rLQT88OeN2m/2O47bivnjHRs33r5t20x/076LeXlXvTlQvde+mRA2/d6sMbX/zkx5udfOpzvx587PfWPbXwl1P/PPnXB5c9v6dzH333vFf5ONzAQrZv8+0/5zzCLM+ffuP9m6s5pDxLgIOzH6PwhzrW6a1R7ssf30S2uFkFrWGgylf9vAc0/XXlaVqrIARbNzr1FRCAiqPgAHkTNgMuEIH/i9rPXOe78lmvhCt8Dp3CB78cyouFDgwgcOjEQfaBkG2wA9kJTTjCHbqQhvoI4hIZeL8h1k55Vouibo5YsxT/YjAW0HNMEpuRtRZmTnsi1GADV3JDEpaxiiBUYRMv+EQgwnGMa6SiDw9oQw/isIhp2yICnQjGiSXQfzE0oxXfeD7SRRCQi5xjI7VYSPCJ8ZFfpOQME3m9QU5SipDsIRk1GUk7fnKT6KIjJilESs+V0pKSDOUoXVnHV3oylrRM4ClNB8pZ5pKVoqxlKj+4Sk5WUpiXvCXldknMVuryl4xMZi+RGcxoqnJmxkQlLKE5TWBms5nS1KY3ublNRzqTjNWUGza/Kc5ughOdneSlLN3py2vWqpzOOOc675nOcLZznPFcpjxhSc9D2TOf7BymOgmKz30eVKH6NJ+neOi3LvIQ/2vL+1wxYXYuGM6SiBDtaCDZOFHJ4WKPdJwXFht6UYl6dKVPZGmi7KWyeJ3xe3eMKUafKUSX6tSCKv2jSFX30V6aKWQDPelOj3pInVJ0pGok5MuMmlD+IXWqSqTqUoEaUYfWlJqKxCNAvUTVsLaRpVfdXktzuik5MjSEYm2rBA0J0bLaralofSkUvbpRsLrVqnq1y0/NGlRy3hSmGoUnRzs6vdWs9LBIvKT41ncxg3JRhiDlKvqqKjui5rGn9FvrLU0q2bc+cKiQnalNjeZXziojqucDrWPVuM7HhvGrmENsX1EY2s/u77VxJKhsBZk+7C32to1VZmZL+0Pf7ta4jP8SrmktK1M0EjeLuT2la5mbwdEOdq6YpSllyDpdnlYXk9fFaW/X+tuM2jW9L/Spao043kSW950W1W5Ph+RH/A73vQTkrSnHMd9+ZjVw9wWwH9nLRG+2Va0TPG2D07pduUa3vub9b4FRG8EFe5C0s33qg0MYuu/C17/H/fCEu6lhqEZYj3VFMNQsLNXACti7L06uivlr2xubWLORhauDCYy8EWMXuivWsY93yuAjgzi8bF1ue52LWxLTmMPA5eNUk1zZJeOYxgEO8WX7O2Qt7xjDYw0rlrcqZiU71cbyUyyYK1xiJZ84mymOcYbnyeQzixjGJSOpYTfM4z6aGdAX7jD/m+W8Oz4/0M/8lPD4qizoSEu3to3ebvxSGl9I3/l9nFa06Bynw9QGOa+FRrRnpyhYI2fZy4lVL6it7EU3/7nUq0YoqmmpZwVads1TJvSYP9zZUc/616bOtH212tUa9lh3xDN0mSUda6kF19L5fXSY56zgurI60JuG9hWFXWlao5m1x8Z0n0PNPG0nOqmvFrWsw03sWp+6esy287PT3ek4Lxrd9442waYt7gSTW3St/jS/VcbrH+8b1ijeLLgXau+G/7OkAI84nQ+M8Xz3mtLZfTKT2z1gVzs5wQm/uMYl/ly7FhywIff4lpX9ZsKK1uDLhnNzldvdBa6cuzL2crDV/zzyGlM42ZMFcrGNnmWQcxnPHC+6ehkL8yiLvOYkV3fAhY5ydltdbB2kN5rdOHOZB7jroS15tnMO4WZXtOMyh3phi9vescMW5xRn+Nt1bm2sDv2uWd/7z6fuYaCf3OR19/bd045snre87R8/uNPjHnSySxnv6M144fX97/NS9uuWXzzfH0/0sGOd8Pg2vOht6WtzX/uvoxe9o1+O0tWrfeGmv3S9z01bY7vc4ipnvdsLOvnYQ97up6847kmte8Yb1cU7ZzTEg59meQO72rOnOfKh//rpS+/Wvx84fRU+7sZPHPMCz/v3iVz9qm/f66G3vc2fD/huex71qhaqrSF3/P9DP53F4+eni8HufqnGe81nfOVGe/q3e/mHV/BEfwMofv7kaeWXfjMGfgIoeBHYemnUf/Bnf/Wnfim3c1R2e7LXZgd4c/hnglHnfwVofVpHfGAnV/8XdCIYfhNIgxaYdKvFbQvIg9R1dPtng0XmgFcng2jXZPvlbCqohOL1gwk4gi24cfFWg4l3g7j2gJ6ngab3e0pXgRSIflIodo4HfPEHbxcodcOXclwIeroWfe/3hU0YhsQXgtQ2eO6VhIe3Xjp4dT7HdGD4eXlohNhGgEioaT2Ih86HeEjXgUN4h2d3fSkofO3ncIW4hIAYc2wYg30Ih3+ohmOIhsNmhnAXh2n/KIYwyHpFGHiQ6IigqIqD6FHYhj+5dk6oiIChl2uCyH10iIEByIZ/B4UceH6954F/eIs7iHAtxoKSuIEsJIu/RIsnuHnKiIFV6IpJhYuVt0GpR4KbWIzs13rMJ4SqN4XiKI0MCF7DOIuaKH1eM4zXGI3fmIzwuIx2qIDA6IXCyIjsmI/SRnVWSITqOI5hFova6IarGIXcmIt7aIy7hoy5V4+R2It6uI8FSY0EqXwTuWe16I9++IyVuIVz94/z2JGJaIC/+IatmJAcCZASmHgjaYkaCWWG2IY4aJLoUIWSl4P9eEwriYnhyFSB6Hv8d4kA2JA9Z108aYrmRH0l+In+/8aSoXiMQlZOLtmJOwmUKNiU37aJOMl5Okk4VCmGVgmCQbl8ErmVIKl9qXg8YCmHX7mU+kiJiKSSmgeVJLmWSNl5B8mPY4mVozhpc2mUsIePrYWXRgiOZGaP21aWUvmUOXl5omhMbKmFbnmVTOmXTtmTgpmFMEmZEEh+GWh+8uiZehmQixiXg+mYZ3iRdXmEo9mF3seJzLSQnpiRe/eRdFmSH1h65rh+7yiZvLmLb7mXWHiFtnlrXBmMc+iNv7mCvXmWzqmKZjeQfRmRdQibmQidpZmcs6mcvCedkwiEabmbzTmN99eI12mRG3ma35mN1Imc99idikiT5Uien8mLsP9YlH8Jl6m5dPMYk4mZng34kLT5ZcQpmO8ZW5Enm01HoM3okCgZhIv5mCHlnmj5bvaIiC8Jjc+plrUJoAcqnAV5mBhZoPXpd3kZlaSYnav5hLp5oiLJk91omfeYlH54kzN4ivkJmem4orHZo7CZoVUZoDhaoXXoi9upkAw6otiXZ7qokTW6dhB5m3xZdjGanthZe2Hpk9qZmfUUj9OZfF0apajphCKKoqTXnypansupoz64jev4kx1KjHTngpOJjkQ6o+w5lM5ope2Yoy+opUkKmJxZpBMql0wqqBe6hgI6nJDJh5XJoaopmk9qlo3qkUP6oJl3iY9KpZHqqE0qoYT/SqJ6CpyfuZkbmptiKpClqKC42ZVy6qCGqqEf+nBMGJ6e2ne5uobdJ5SiOqDVKJPxSYZsN6chaafiiaqvOp5uqqnWiKmP2KyfyqaQCqfKmqwumqKS6o7kqKoUaWCMWayD2m+66nqg6p14+quhyYtLGp3ouqowCqj9+J5HWqKXeqeZ6qG2WqaNaabxKqfzWqnRaq+jeqatKX8vKqv0qpjISq4umaA/2qvjiqbZKrHFZ6QBy6I12YkOS6ekeZmVKKTTuqcXC64Ly5q8WqgH+5oRS64ViY0yRqrS+o4oy7An6a2JaqmLWrCcKqkxy6wZS58g6643a6PrhrB1uqwTq7Mk/7upKXmy5rqf/bqm59qWOCu189em+gq0kyqTXWaeCKmkO7ulwZihp0qsJsufDVuYrmmX+Wqxapq0BkmsPqu14TqgG7u2pXqt9dq28MmdTjusNku2dOWVIqu3lJenIWqLoemyVHilx4mx3eq2QDq0i2uscSusXJq5QSu4NEq4sAq58Dq10MeoRHuaaJuVnYu3htmnYRqytSq3Uvq1gWuwHsu1Xbi6cGu7lFu1sHuj1sm4Y6uetIulPVu5oDlvRZuywRq6wSmvrQu2vhm5Xmu4Joq4Ucuv8+m3iMmQEGu1lluzs9q3PGu949u8zlq9uNuqo1utW0uv1Cu9omuqHau2wP/7vdWZpd4rrhQrtL3LrfhZuOELsP7brv46v5ebpkhbsZvro4FJtU37snFLlPqLq92ruy0bvNxbrqEqs6SbteK7weeprmKLwCvLwddbwBBcpUyrwCicug3qpF0bjwAMuiDqqoPLwjIcwOk6gVDavsMrsHVrtkJMvxaMtTfcwKzIpT5smjmLBzSsrUVcu3NrocQ7wqxLwE0cxE88mworxUMcu2T6sbJbwoc4w118vF58vwwsd9S6r8hLovo1stCavXDMrfD7v8J7n4CrxXUsx8aLr5Jrxy0qyKjLuVOMh9l3x8cLxczbw+urxH7Mx4ccq/a5dTzMddP7pZBchj9syLf/a8SOPKPA6sIst6Ob/LyBjMeE3LgVPJNArL2rvMVle8X/iso17Lj3u8feSMrmG7ZnpcqcTKvRK59JnMCijL2DXJNiCch0XMizu8xkGccxrL72O83IdcrBnMpsG8ZTesQXrLpnXMZiDMv4+80SfI4rnL9z3MLhHMi/a8s5/LPlXGcebMDZDM6vXLqUrMcfTMsnfKjvus7N3M76LM7727d0y8WPPGgRXLHvS8KJ26NMDL53+7noTIgCrbIBvcRpfNCubHZgvMsCrMlZjK11S9F2y8rUnND+fNHHfFQPO9D4XNDFa7sp3cs2WctX9sXFScVY7LzODM+UKs8o7dIZPZWB/5rLjciuL+zNC5zB8qXUAfW6eazBTT3GYZzSWI0fU01PVb3SVw29VmzDx2rVUm3SkenVfcy3ITymuVvTUU2Yaa1bac3VNCtQBbvVY80iYJ3Uds3Xh3zXz0rQyBxQPX3YnvvOaEzBZE3SgN3Yfb3W1eTX2tvIJizCZb3RMJzYla3WkM2+GhvYxly/+czAzPzQwryrPq209KjRe/vPTK245ly+Wn3UCfvSMS3FCr3PoPy2m11U4QvGIs2q7anORA2zeUvTqe2fUJvCGJ3cDX3csJ3b7szOzM3NeP3cMG3U0m3P8RzdQV3YpX24rc3bt13QYPrdyH3ON13SM42hxY3b2/883ki22+jdyaLtxidNoHDN3W6dtmaM1DgM3u1dzddN3rWt3fo93+HN0w5dyrQ9zNs9sE+bvJhczDxq2pMsyMHNmrJc0ana20F6yW9qrRjMyyzd3/JtoKMMzbFc3eTs4QHezRy+rnxastKMqC0Z4yH94hLe2p984/Lbwbcq4rds4C3d3JrZz0W94kQ+z4ps4jAOzAWu4M6tuTtt2KZL4yCuzCHO5Qy+2eQb31XO3v8941db5G/Muw0e1+kLkSRuzRg+5Yqtwsnc5tD95vHLzTit5RVe5+rtxGROxo+N4KsN5yDs3z/twBaO0LYN5UrOV0y+vP0L30vb6DQ+1Nhd3gD/fuIczd82benA3eOq/dtbHur37eSC/ukGDdrpXer0fbSoDuRPnumtDtFILNNjztqKbup7fc9lfuv8nevD/uUdrtexbuWMjr7CrepuHldY7r4R/eHJvuqTbefN7ugWbdzbbuQdDdT17empPs4LTuBnrtuULtEbrtk6vOyYru2a/ux6bpzkBZ4E96e4fLqE7d66fpQ7/FDdnqqETs7bCqFL7e2JTcyZdO9gPu0A79T8fuAtTtWM/K3uNvDnu7cGH80R6uwKL7yorZUZn+iDrcuzPdwgH/KdqZ8kz+e+6th0ztbmrvJe/seYycbY/p+SHu8vbPJeCvHWhPH4Xum+nNkE/zvRVfzX+h7wQ+/wKp3lvwztEy7mu8vyaM7inP7fm75Xto7u8L7cUc7hlTzrZ4vYht7yas6/YD/Pajyzf6vuCf/0E/yKZ8/tl+7r/u7xMk/uH//y7v7g0732p972kfvJZM/s9S745p3O64314V72RJziGkzzA2zWXd7w2f74+Uz3MD/zEs/2iL74kF73EB7KiV/4OT75Ry/3tU7wi86Mdk/hjA/5Zr/6+x738l7yWU+ho5/y7Z7gEZ7TPu6npi/jC4r0TA/u7076vA78or/Ish78px/nH9zKB7/wai/geG/59D74NJ/nm+/8rO/J8w7Vzz/u9Gzq4P/jCn39Ha+8o//v2S4l5Osv7cc+4tQe8+Vv/PPf+C6f/edO3Uue/J5fx22M91wf+7fP//Jf4mHOz2us4sTv9yCd+cNf4q1O8MOv86B//MEe5L3exlNP/c2/9T/O9UYP0iXe6lwv+UDP/P4f6SDM9dRf41Lf6W0c2qYc4cDe57M90o6P2a7M/nvf9F+f99l9//4/5/xP/tp//JFtyhEO7H0+2yPt+Jjtyuy/903/9Xmf3ffv/3PO/+Sv/ccf2aYc4cDe57M90o6P2a7M/nvf9F+f99l9//4/5/xP/tp//JFtyhEO7H0+2yPt+Jjtyuy/903/9Xmf3ffv/3PO/+Sv/cd/7YvNoO8/uX//bvR3v+euPPz4jfqdK+pGn8zub7Spz+5h+v7EjvIXP/5jOvyjXex7vuN879uxndd0bbRUL8liXftB/uPvT+wof/HjP6bDP9rFvuc7zve+Hdt5TddGS/WSLNa1H+Q//v7EjvIXP/5jOvyjXex7vuN879uxndd0bbRUL8liXftB/uPvT+wof/HjP6bDP9rFvuc7zve+vetaP/15L96YOdKOL/xXH+HFPv6tX+yeT+z6z9aw/8o3H+HFPv6tX+yeT+z6z9aw/8o3H+HFPv6tX+yeT+z6z9aw/8o3H+HFPv6tX+yeT+z6z9aw/8o3H+HFPv6tX+yeT+z6z9aw/8o3H+HF/z7+rV/snk/s+s/WsP/KNx/hxT7+rV/snk/s+s/WsP/KNx/hxT7+rV/snk/s+s/WsP/KNx/hrP70xD3n/P/oNz/4cs78v++69i/W0h/koP/K9Q+u3S/+tO7ZOa/6vg/6r1z/4Nr94k/rnp3zqu/7oP/K9Q+u3S/+tO7ZOa/6vg/6r1z/4Nr94k/rnp3zqu/7oP/K9Q+u3S/+tO7ZOa/6vg/6r1z/4Nr94k/rnp3zqu/7oP/K9Q+u3S/+tO7ZOa/6vg/6r1z/4Nr94k/r+S/9oI7sev/o9U/XRivslhzsPE/dAr/8FI/9hEz88a+oXt/v3G/9LBv6ea/8dUz8fy/8+f8eyfhv+E3u9Miu949e/3RttMJuycHO89Qt8MtP8dhPyMQf/4rq9f3O/dbPsqGf98pfx8T/98Kf75GM/4bf5E6P7Hr/6PVP10Yr7JYc7DxP3QK//BSP/YRM/PGvqF7f79xv/Swb+nmv/HVM/H8v/Pkeyfjf9ZEM9Q+v/E9f/6q/7p7f98nM6pY99rK/xVKO66Pt2b+/7mlv2WMv+1ss5bg+2p79++ue9pY99rK/xVKO66Pt2b+/7mlv2WMv+1ss5bg+2p79++ue9pY99rK/xVKO66Pt2b+/7mlv2WMv+1ss5bg+2p79++ue9pY99rK/xVKO66Pt2b+/7mlv2WMv+1v/LOW4Ptqe/fvr3vN9H/2dqvtoH/mv/P6mLPygn/d9jvIqXvmNH9bXDPTKz8/jrOE9z8/9z/vdHdqSjM3CD/p53+cor+KV3/hhfc1Ar/z8PM4a3vP83P+8392hLcnYLPygn/d9jvIqXvmNH9bXDPTKz8/jrOE9z8/9z/vdHdqSjM3CD/p53+cor+KV3/hhfc1Ar/z8PM4a3vP83P+8392hLcnYLPygn/fGfvOoT/15L96AH/bbf8APbMm1b/RvjfIqzpzTT/jbf8APbMm1b/RvjfIqzpzTT/jbf8APbMm1b/RvjfIqzpzTT/jbf8APbMm1b/RvjfIqzpzTT/jbf8AP/2zJtW/0b43yKs6c00/423/AD2zJtW/0b43yKs6c00/423/AD2zJtW/0b43yKs6c00/423/AD2zJtW/0b43yKs6c00/423/AD2zJtU/9Ol388X/WGY7yf27f/a/zTe70vi2jZ53hKP/n9t3/Ot/kTu/bMnrWGY7yf27f/a/zTe70vi2jZ53hKP/n9t3/Ot/kTu/bMnrWGY7yf27f/a/zTe70vi2jZ53hKP/n9t3/Ot/kTu/bMnrWGY7yf27f/a/zTe70vi2jZ53hKP/n9t3/Ot/kTu/bMnrWGY7yf27f/a/zKn/yA+76cA+7br/jxfz+ktzZv575CLryAJ3++e/kMv9a8dIf+7kf8VIfxRfu8tkvyZ3965mPoCsP0Omf/04uoxUv/bGf+xEv9VF84S6f/ZLc2b+e+Qi68gCd/vnv5DJa8dIf+7kf8VIfxRfu8tkvyZ3965mPoCsP0Omf/04uoxUv/bGf+xEv9VF84S6f/ZKslPsN9YJt/5T/510/2yNdzDcP6Gze64ev4sTt3a9N9U9/84DO5r1++CpO3N792lT/9DcP6Gze64ev4sTt3a9N9U9/84DO5r1++CpO3N792lT/9DcP6Gze64ev4sTt3a9N9U9/84DO5r1++CpO3N792lT/9DcP6Gze64ev4sTt3a9N9U9/84DO5r1++CpO3N7//dpU//SnrfHC/+NcL/my/er5TeWH7uqhPfcerc2cb/G5v/yRrP7gyrHYjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37rf5d3/XpP/xKP/fYruE9P7ks2/ViJeW4rtzC3vPnTutSjutdH/h43uuiHuTtH+M7j8hd3/Ub/+eS//co7roxvvOI3PVdv/F/Lvl/j+KuG+M7j8hd3/Ub/+eS///3KO66Mb7ziNz1Xb/xfy75f4/irhvjO4/IXd/1G//nkv/3KO66Mb7ziNz1Xb/xfy75f4/irhvjO0/4Rt/6xL3fKp/zc677QO/gkV/w90/c+63yOT/nug/0Dh75BX//xL3fKp/zc677QO/gkV/w90/c+63yOT/nug/0Dh75BX//xL3fKp/zc677QO/gkV/w90/c+63yOT/nug/0Dh75BX//xL3fKp/zc677QO/gkV/w90/c+63yOT/nug/0Dh75BX//xL3fKp/zc677QO/gXV/jDLrzNZ772p/3xs710E/IXX/5W7zzNZ772p/3xs710E/IXX/5W7zzNZ772p/3xv/O9dBPyF1/+Vu88zWe+9qf98bO9dBPyF1/+Vu88zWe+9qf98bO9dBPyF1/+Vu88zWe+9qf98bO9dBPyF1/+Vu88zWe+9qf98bO9dBPyF1/+Vu88zWe+9qf98bO9dBPyF1/+Vu88zWe+9qf98bO9dAf1mh954Tc21xf6Mmc/4t94Zgf2W7b/Vsv1z/c2wSf0zkf6el/82LP0EKt4qfd/iX++ReO+ZHttt2/9XL9w71N8Dmd85Ge/jcv9gwt1Cp+2u1f4p9/4Zgf2W7b/Vsv1z/c2wSf0zkf6el/82LP0EKt4qfd/iX++ReO+ZHttt2/9XL9w71N8Dmd85Ge/jcv9gz/LdQqftrtX+Kff+GYH+yh7+pOr3jUbebib/UXTsjdT90C//no//PZ3/fRr/xzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/2W/eM73/wbL9dN//WuHtpCj7lyXec73/wbL9dN//WuHtpCj7lyXec73/wbL9dN//WuHtpCj7lyXec73/wbL9dN//WuHtpCj7lyXec73/wb/y/XTf/1rh7aQo+5cl3nO9/8Gy/XTf/1rh7aQo+5cl3nO9/8Gy/XTf/1rh7aQo+5cl3nO9/8Gy/XTf/1rh7aQo+5cl3nO9/8Gy/XTf/1rh7aQo+5cl3nu45UT536U9+5p63iaf70BP/6YvXUqT/1nXvaKp7mT0/wry9WT536U9+5p63iaf70BP/6YvXUqT/1nXvaKp7mT0/wry9WT536U9+5p63iaf70BP/6YvXUqT/1nXvaKp7mT0/wry9WT536U9+5p63iaf70BP/6YvXUqT/1nXvaKp7mT0/wry9WT536U9+5p63iaf70BH/zYm/84T/4ns31PA/4Wy/Xdb7zC//994Ae5vx88rVP/Xkvqzm/2GIt/UHe/tbf5Oye/F/P2X+P2Xf/9TV+4YTs5+Ps/k0u1D++8wv994Ae5vx88rVP/Xkvqzm/2GIt/UHe/tbf5Oye/F/P2X+P2Xf/9TV+4YTs5+Ps/k0u1D++8wv994Ae5vx88rVP/Xkvqzm/2GIt/UHe/tbf5Oye/F/f/JGf/r39/m6r4RQ/+64+9Yls/qgP/XUuo4oKwk898YpfzJWP44ee/r39/m6r4RQ/+64+9Yls/qgP/XUuo4oKwk898YpfzJWP44ee/r39/m6r4RQ/+64+9Yls/qgP/XUuo4oKwk898YpfzJWP44ee/r39/m7Tq+EUP/uuPvWJbP6oD/11LqOKCsJPPfGKX8yVj+OHnv69/f5uq+EUP/uuPvWJLNdx2unF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//avwEFAAA7' . '" width="220" height="220"></qr-code>
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