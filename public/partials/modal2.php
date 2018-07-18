<?php
/**
 * Created by PhpStorm.
 * User: aaires
 * Date: 17/07/18
 * Time: 09:57
 */
?>

<div class="modal">

	<div class="modal-dialog open opened" role="document">

		<div class="modal-content long">

			<div class="content">

				<div class="invoice">
					<div class="top-header">
						<div class="header">
							<div class="header__icon">
								<img class="header__icon__img" src="assets/images/bitpay-logo-white.svg">
							</div>
							<!----><div class="close-icon">
								<img src="assets/images/close-icon.svg">
							</div>
						</div>

						<div class="timer-row">
							<div class="timer-row__progress-bar" style="width: 4.16533%;"></div>
							<!----><div class="timer-row__spinner">
								<bp-spinner>
									<svg xml:space="preserve" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 50 50" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px">
    <path d="M11.1,29.6c-0.5-1.5-0.8-3-0.8-4.6c0-8.1,6.6-14.7,14.7-14.7S39.7,16.9,39.7,25c0,1.6-0.3,3.2-0.8,4.6l6.1,2c0.7-2.1,1.1-4.3,1.1-6.6c0-11.7-9.5-21.2-21.2-21.2S3.8,13.3,3.8,25c0,2.3,0.4,4.5,1.1,6.6L11.1,29.6z"></path>
  </svg>
								</bp-spinner>
							</div>
							<div class="timer-row__message">
								<!----><span>
                    <!----><span i18n="">Awaiting Payment...</span>
                    <!---->
                  </span>
								<!---->
							</div>
							<div class="timer-row__time-left">14:22</div>
						</div>
					</div>

					<div class="order-details">

						<!----><div class="single-item-order">
							<div class="single-item-order__row">
								<div class="single-item-order--left">
									<div class="single-item-order--left__name">
										Gift Off
									</div>
									<div class="single-item-order--left__description">
										Payment for card "Google Play"
									</div>
								</div>

								<!---->
							</div>

							<!----><div class="single-item-order__row selected-currency">
								<div class="single-item-order--left">
									<div class="single-item-order--left__currency">
										<img src="assets/images/currency_logos/btc.svg">
										Bitcoin
									</div>
								</div>

								<div class="single-item-order--right">
									<rate class="ex-rate">
										<!----><div>
											1 BTC = 5,005.32 GBP
										</div>
										<!---->
									</rate>
								</div>
							</div>
						</div>

						<!---->
						<!----><line-items class="expanded"><div class="line-items">
								<!----><div>
									<!---->
									<div class="line-items__item">
										<div class="line-items__item__label" i18n="">Payment Amount</div>
										<div class="line-items__item__value">0.001998 BTC</div>
									</div>
									<div class="line-items__item">
										<div class="line-items__item__label">
											<span i18n="">Network Cost</span>
											<a href="https://help.bitpay.com/paying-with-bitcoin/why-am-i-being-charged-an-additional-network-cost-on-my-bitpay-invoice" target="_blank">
												<img src="assets/images/invoice-fee-question.svg">
											</a>
										</div>
										<div class="line-items__item__value">0.000007 BTC</div>
									</div>
									<div class="line-items__item line-items__item--total">
										<div class="line-items__item__label" i18n="">Total</div>
										<div class="line-items__item__value">0.002005 BTC</div>
									</div>
								</div>

								<!---->
							</div>
						</line-items>
						<div class="payment-tabs">
							<div class="payment-tabs__tab active" id="scan-tab">
								<span i18n="">Scan</span>
							</div>
							<div class="payment-tabs__tab" id="copy-tab">
								<span i18n="">Copy</span>
							</div>
							<div class="payment-tabs__slider"></div>
						</div>
					</div>

					<div adjust-height="" class="payment-box" style="">
						<info-alert id="disabled-reason-alert"><div class="info-alert">
								<div class="info-alert__hide-button">
									<img src="assets/images/ios-close-outline.svg">
								</div>
								<div class="info-alert__content-wrapper">
									<div class="info-alert__content">
										<img class="info-alert__icon" src="assets/images/info-icon-sm.svg">
										<div class="info-alert__heading" i18n="">Currency Unavailable</div>
										<div class="info-alert__body">
        <span>
          <!---->
          <!---->
          <!---->
          <!---->
          <!----><span i18n="" id="generalDisabled">This currency is temporarily unavailable.</span>
        </span>
											<span i18n="">Please use a different currency to complete your payment.</span>
										</div>
									</div>
								</div>
							</div></info-alert>

						<div class="bp-view" id="currency-selector">
							<currency-selector><!---->
								<div class="non-iframe">
									<div class="instructions">
										<h3 class="header" i18n="">Choose Currency</h3>
										<p i18n="">To complete your payment, please choose one of the following currency options.</p>
									</div>

									<div class="currency-list">
										<!----><div class="currency-list__item">
											<div class="item-container loading" id="currency-selector__currency--btc">
          <span class="logo-and-name">
            <span class="currency-logo">
              <img class="currency-logo__icon" src="assets/images/currency_logos/btc.svg">
            </span>
            <span class="currency-text">
              Bitcoin
              <br>
              <span class="fee">
                <!---->
                <!----><span>
                  <span i18n="">Network Cost</span>:
                  <network-cost>
	                  <!----><span>
    <span>£0.04</span>
    <span>GBP</span>
  </span>
                  </network-cost>
                </span>
              </span>
            </span>
          </span>
												<!----><span class="right-arrow-section">
            BTC
            <img class="right-arrow" src="assets/images/chevron.svg">
          </span>
											</div>
										</div><div class="currency-list__item">
											<div class="item-container loading" id="currency-selector__currency--bch">
          <span class="logo-and-name">
            <span class="currency-logo">
              <img class="currency-logo__icon" src="assets/images/currency_logos/bch.svg">
            </span>
            <span class="currency-text">
              Bitcoin Cash
              <br>
              <span class="fee">
                <!---->
                <!----><span>
                  <span i18n="">Network Cost</span>:
                  <network-cost>
	                  <!----><span>
    <span>£0.00</span>
    <span>GBP</span>
  </span>
                  </network-cost>
                </span>
              </span>
            </span>
          </span>
												<!----><span class="right-arrow-section">
            BCH
            <img class="right-arrow" src="assets/images/chevron.svg">
          </span>
											</div>
										</div>
									</div>
								</div>


								<!---->
							</currency-selector>
						</div>

						<div class="bp-view payment scan active" id="scan">
							<div class="payment__scan">

								<div class="payment__details__instruction__open-wallet hidden-sm-up">

									<!----><a class="payment__details__instruction__open-wallet__btn action-button action-button--secondary">

										<span i18n="">Show QR code</span>
										<img class="m-qr-code-icon" src="assets/images/qr-code.svg">
									</a>

									<!----><div>
										<a class="payment__details__instruction__open-wallet__btn action-button" href="bitcoin:?r=https://bitpay.com/i/9ssav4zPhdUGocEzHpkgQR">
											<span i18n="">Open in wallet</span>
											<img class="payment__details__instruction__open-wallet__btn__external-link" src="assets/images/external-link.svg">
										</a>
									</div>



									<div class="qr-code-container hide">
										<!----><div class="qr-codes">
											<qr-code class="payment__scan__qrcode"><img src="data:image/gif;base64,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" width="220" height="220"></qr-code>
											<manual-box runbp="">
												<!---->



												<!----><div ngxclipboard="">
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
											<div class="payment__details__instruction__open-wallet">
												<a class="payment__details__instruction__open-wallet__btn action-button" href="bitcoin:?r=https://bitpay.com/i/9ssav4zPhdUGocEzHpkgQR">
													<span i18n="">Open in wallet</span>
													<img class="payment__details__instruction__open-wallet__btn__external-link" src="assets/images/external-link.svg">
												</a>
											</div>
										</div>
									</div>
								</div>

								<!----><div class="qr-codes hidden-xs-down qr-code-container fade-in-up">
									<qr-code class="payment__scan__qrcode"><img src="data:image/gif;base64,R0lGODdh3QHdAYAAAAAAAP///ywAAAAA3QHdAQAC/4SPqcvtHKKcVLaKs970LY55oqFd2YiAaoQeq2q+MtiSc1nn+s730By7CTu1my91+oWKw0qu6VRCh8+p5YjNan1GqRXIlGlxn2Trywpbg+hX9buNy+deWL1NU6/GZgUZ1fa2doe3IThFl6jIJ0ZY2DfShfWXQCkSqAfF9miY2bQIGnpoV8aZdwY2CYm0hArnSeVoOjH6KXqLK7vqMstR65tlydsKiPYrtNlL5DqY65wr6acs/BDNtWtDHGkMi6zbe2z9PK4oPjwdxbwXjA1A7YCprvk9G55KfoTebp7ttsjvrp29RvKuFASmShurdNIIFnNYSR/DbRJp0VvW0F+5e/8RFfKI93DiJY493pkk6QFgRYwUVwZIhjDjuo0QF7Isye3gy27vjsHcWUpjyJkyKw6sd9FiUKJ0VArkibLa06Wk8u27WjNl1IAugQ41mtRg0VOJnHrcAbLlTa1Z0WIVeY7pyLZcXR419fMkXUZCO8J1+0rnXaVWz9Zd208u2742weqclvet4jhm/+pIO9dyZMM+HW3WHDYNVYmDH31GfLhq0609oe717Bq0YNijY6pV3Zh0bLyhvfqdvKUy6tK2Mw/vzRe1XsaLgXcV/VXsNcPLnUsmjC938bjYjc9LaLl1bd/ayXOXXr56949Tx0KfHv462d/rE9fHJ/z+adzsqbf/p4/eee+Vt1+A9hl42X8EsuNfg8wdOKCA5mWXH4IFbgdYfJzR5t6EqSFYYYQZHjdecvepN9+CJWbXIYoiwpNTYRrK1uFPNiLHIXwk1sjgjMqxJt+L5ITo4YWdgPfjhisuyeN4LhbZTWdInhjkkU1eySKEVgIoZG/iRffil1x6+KSRN05pYY44OZjkgx+GqeAtRBK35Zo+6qemhGe2WKWJaTKpI5Vs8qflnlm+WaeeuxGa4KB/YqkipBKW2eOOY/oJp6MYImpolnMummJ/dz56aZ6F4gionZZGGiiIcWoJEKbeOPkqopSi2eVjMbaa6ai5ggllqqK2ueqkW8lKpJjp//VZKZ66BiYjsYLSeBuprKo6bbGwHtusLbQqyaemwQKL7K7YuiqurFJGm62z4bpZLl2xoirpiO5WW4i6pnLa7a+l4kpmrZjJMfC24I55K7vWNseJvsKeCvC6vAZ88LKvAZyswEBWzPDCUoHTL8W+josvydcO267HtnKL8cbw0vuvwv56Z1rInSoacZQ272uksih7+662K6e7c8vQTnwzxDKbjPPSScN49BwFD02txSM7vPTUEp+sNNI65/zwodAwGzYoPhs8csIl90y201+7XbbYoqjNdShnUy1tqF3T3CsiPL+9dtH1yi1n2zGPzTHaeW9695Ofgk2u4IcTjvjVf//TVLWx4tLdcd/fDb5150x/bDnlz3C+d+GJ450y4xpv7rLQT88OeN2m/2O47bivnjHRs33r5t20x/076LeXlXvTlQvde+mRA2/d6sMbX/zkx5udfOpzvx587PfWPbXwl1P/PPnXB5c9v6dzH333vFf5ONzAQrZv8+0/5zzCLM+ffuP9m6s5pDxLgIOzH6PwhzrW6a1R7ssf30S2uFkFrWGgylf9vAc0/XXlaVqrIARbNzr1FRCAiqPgAHkTNgMuEIH/i9rPXOe78lmvhCt8Dp3CB78cyouFDgwgcOjEQfaBkG2wA9kJTTjCHbqQhvoI4hIZeL8h1k55Vouibo5YsxT/YjAW0HNMEpuRtRZmTnsi1GADV3JDEpaxiiBUYRMv+EQgwnGMa6SiDw9oQw/isIhp2yICnQjGiSXQfzE0oxXfeD7SRRCQi5xjI7VYSPCJ8ZFfpOQME3m9QU5SipDsIRk1GUk7fnKT6KIjJilESs+V0pKSDOUoXVnHV3oylrRM4ClNB8pZ5pKVoqxlKj+4Sk5WUpiXvCXldknMVuryl4xMZi+RGcxoqnJmxkQlLKE5TWBms5nS1KY3ublNRzqTjNWUGza/Kc5ughOdneSlLN3py2vWqpzOOOc675nOcLZznPFcpjxhSc9D2TOf7BymOgmKz30eVKH6NJ+neOi3LvIQ/2vL+1wxYXYuGM6SiBDtaCDZOFHJ4WKPdJwXFht6UYl6dKVPZGmi7KWyeJ3xe3eMKUafKUSX6tSCKv2jSFX30V6aKWQDPelOj3pInVJ0pGok5MuMmlD+IXWqSqTqUoEaUYfWlJqKxCNAvUTVsLaRpVfdXktzuik5MjSEYm2rBA0J0bLaralofSkUvbpRsLrVqnq1y0/NGlRy3hSmGoUnRzs6vdWs9LBIvKT41ncxg3JRhiDlKvqqKjui5rGn9FvrLU0q2bc+cKiQnalNjeZXziojqucDrWPVuM7HhvGrmENsX1EY2s/u77VxJKhsBZk+7C32to1VZmZL+0Pf7ta4jP8SrmktK1M0EjeLuT2la5mbwdEOdq6YpSllyDpdnlYXk9fFaW/X+tuM2jW9L/Spao043kSW950W1W5Ph+RH/A73vQTkrSnHMd9+ZjVw9wWwH9nLRG+2Va0TPG2D07pduUa3vub9b4FRG8EFe5C0s33qg0MYuu/C17/H/fCEu6lhqEZYj3VFMNQsLNXACti7L06uivlr2xubWLORhauDCYy8EWMXuivWsY93yuAjgzi8bF1ue52LWxLTmMPA5eNUk1zZJeOYxgEO8WX7O2Qt7xjDYw0rlrcqZiU71cbyUyyYK1xiJZ84mymOcYbnyeQzixjGJSOpYTfM4z6aGdAX7jD/m+W8Oz4/0M/8lPD4qizoSEu3to3ebvxSGl9I3/l9nFa06Bynw9QGOa+FRrRnpyhYI2fZy4lVL6it7EU3/7nUq0YoqmmpZwVads1TJvSYP9zZUc/616bOtH212tUa9lh3xDN0mSUda6kF19L5fXSY56zgurI60JuG9hWFXWlao5m1x8Z0n0PNPG0nOqmvFrWsw03sWp+6esy287PT3ek4Lxrd9442waYt7gSTW3St/jS/VcbrH+8b1ijeLLgXau+G/7OkAI84nQ+M8Xz3mtLZfTKT2z1gVzs5wQm/uMYl/ly7FhywIff4lpX9ZsKK1uDLhnNzldvdBa6cuzL2crDV/zzyGlM42ZMFcrGNnmWQcxnPHC+6ehkL8yiLvOYkV3fAhY5ydltdbB2kN5rdOHOZB7jroS15tnMO4WZXtOMyh3phi9vescMW5xRn+Nt1bm2sDv2uWd/7z6fuYaCf3OR19/bd045snre87R8/uNPjHnSySxnv6M144fX97/NS9uuWXzzfH0/0sGOd8Pg2vOht6WtzX/uvoxe9o1+O0tWrfeGmv3S9z01bY7vc4ipnvdsLOvnYQ97up6847kmte8Yb1cU7ZzTEg59meQO72rOnOfKh//rpS+/Wvx84fRU+7sZPHPMCz/v3iVz9qm/f66G3vc2fD/huex71qhaqrSF3/P9DP53F4+eni8HufqnGe81nfOVGe/q3e/mHV/BEfwMofv7kaeWXfjMGfgIoeBHYemnUf/Bnf/Wnfim3c1R2e7LXZgd4c/hnglHnfwVofVpHfGAnV/8XdCIYfhNIgxaYdKvFbQvIg9R1dPtng0XmgFcng2jXZPvlbCqohOL1gwk4gi24cfFWg4l3g7j2gJ6ngab3e0pXgRSIflIodo4HfPEHbxcodcOXclwIeroWfe/3hU0YhsQXgtQ2eO6VhIe3Xjp4dT7HdGD4eXlohNhGgEioaT2Ih86HeEjXgUN4h2d3fSkofO3ncIW4hIAYc2wYg30Ih3+ohmOIhsNmhnAXh2n/KIYwyHpFGHiQ6IigqIqD6FHYhj+5dk6oiIChl2uCyH10iIEByIZ/B4UceH6954F/eIs7iHAtxoKSuIEsJIu/RIsnuHnKiIFV6IpJhYuVt0GpR4KbWIzs13rMJ4SqN4XiKI0MCF7DOIuaKH1eM4zXGI3fmIzwuIx2qIDA6IXCyIjsmI/SRnVWSITqOI5hFova6IarGIXcmIt7aIy7hoy5V4+R2It6uI8FSY0EqXwTuWe16I9++IyVuIVz94/z2JGJaIC/+IatmJAcCZASmHgjaYkaCWWG2IY4aJLoUIWSl4P9eEwriYnhyFSB6Hv8d4kA2JA9Z108aYrmRH0l+In+/8aSoXiMQlZOLtmJOwmUKNiU37aJOMl5Okk4VCmGVgmCQbl8ErmVIKl9qXg8YCmHX7mU+kiJiKSSmgeVJLmWSNl5B8mPY4mVozhpc2mUsIePrYWXRgiOZGaP21aWUvmUOXl5omhMbKmFbnmVTOmXTtmTgpmFMEmZEEh+GWh+8uiZehmQixiXg+mYZ3iRdXmEo9mF3seJzLSQnpiRe/eRdFmSH1h65rh+7yiZvLmLb7mXWHiFtnlrXBmMc+iNv7mCvXmWzqmKZjeQfRmRdQibmQidpZmcs6mcvCedkwiEabmbzTmN99eI12mRG3ma35mN1Imc99idikiT5Uien8mLsP9YlH8Jl6m5dPMYk4mZng34kLT5ZcQpmO8ZW5Enm01HoM3okCgZhIv5mCHlnmj5bvaIiC8Jjc+plrUJoAcqnAV5mBhZoPXpd3kZlaSYnav5hLp5oiLJk91omfeYlH54kzN4ivkJmem4orHZo7CZoVUZoDhaoXXoi9upkAw6otiXZ7qokTW6dhB5m3xZdjGanthZe2Hpk9qZmfUUj9OZfF0apajphCKKoqTXnypansupoz64jev4kx1KjHTngpOJjkQ6o+w5lM5ope2Yoy+opUkKmJxZpBMql0wqqBe6hgI6nJDJh5XJoaopmk9qlo3qkUP6oJl3iY9KpZHqqE0qoYT/SqJ6CpyfuZkbmptiKpClqKC42ZVy6qCGqqEf+nBMGJ6e2ne5uobdJ5SiOqDVKJPxSYZsN6chaafiiaqvOp5uqqnWiKmP2KyfyqaQCqfKmqwumqKS6o7kqKoUaWCMWayD2m+66nqg6p14+quhyYtLGp3ouqowCqj9+J5HWqKXeqeZ6qG2WqaNaabxKqfzWqnRaq+jeqatKX8vKqv0qpjISq4umaA/2qvjiqbZKrHFZ6QBy6I12YkOS6ekeZmVKKTTuqcXC64Ly5q8WqgH+5oRS64ViY0yRqrS+o4oy7An6a2JaqmLWrCcKqkxy6wZS58g6643a6PrhrB1uqwTq7Mk/7upKXmy5rqf/bqm59qWOCu189em+gq0kyqTXWaeCKmkO7ulwZihp0qsJsufDVuYrmmX+Wqxapq0BkmsPqu14TqgG7u2pXqt9dq28MmdTjusNku2dOWVIqu3lJenIWqLoemyVHilx4mx3eq2QDq0i2uscSusXJq5QSu4NEq4sAq58Dq10MeoRHuaaJuVnYu3htmnYRqytSq3Uvq1gWuwHsu1Xbi6cGu7lFu1sHuj1sm4Y6uetIulPVu5oDlvRZuywRq6wSmvrQu2vhm5Xmu4Joq4Ucuv8+m3iMmQEGu1lluzs9q3PGu949u8zlq9uNuqo1utW0uv1Cu9omuqHau2wP/7vdWZpd4rrhQrtL3LrfhZuOELsP7brv46v5ebpkhbsZvro4FJtU37snFLlPqLq92ruy0bvNxbrqEqs6SbteK7weeprmKLwCvLwddbwBBcpUyrwCicug3qpF0bjwAMuiDqqoPLwjIcwOk6gVDavsMrsHVrtkJMvxaMtTfcwKzIpT5smjmLBzSsrUVcu3NrocQ7wqxLwE0cxE88mworxUMcu2T6sbJbwoc4w118vF58vwwsd9S6r8hLovo1stCavXDMrfD7v8J7n4CrxXUsx8aLr5Jrxy0qyKjLuVOMh9l3x8cLxczbw+urxH7Mx4ccq/a5dTzMddP7pZBchj9syLf/a8SOPKPA6sIst6Ob/LyBjMeE3LgVPJNArL2rvMVle8X/iso17Lj3u8feSMrmG7ZnpcqcTKvRK59JnMCijL2DXJNiCch0XMizu8xkGccxrL72O83IdcrBnMpsG8ZTesQXrLpnXMZiDMv4+80SfI4rnL9z3MLhHMi/a8s5/LPlXGcebMDZDM6vXLqUrMcfTMsnfKjvus7N3M76LM7727d0y8WPPGgRXLHvS8KJ26NMDL53+7noTIgCrbIBvcRpfNCubHZgvMsCrMlZjK11S9F2y8rUnND+fNHHfFQPO9D4XNDFa7sp3cs2WctX9sXFScVY7LzODM+UKs8o7dIZPZWB/5rLjciuL+zNC5zB8qXUAfW6eazBTT3GYZzSWI0fU01PVb3SVw29VmzDx2rVUm3SkenVfcy3ITymuVvTUU2Yaa1bac3VNCtQBbvVY80iYJ3Uds3Xh3zXz0rQyBxQPX3YnvvOaEzBZE3SgN3Yfb3W1eTX2tvIJizCZb3RMJzYla3WkM2+GhvYxly/+czAzPzQwryrPq209KjRe/vPTK245ly+Wn3UCfvSMS3FCr3PoPy2m11U4QvGIs2q7anORA2zeUvTqe2fUJvCGJ3cDX3csJ3b7szOzM3NeP3cMG3U0m3P8RzdQV3YpX24rc3bt13QYPrdyH3ON13SM42hxY3b2/883ki22+jdyaLtxidNoHDN3W6dtmaM1DgM3u1dzddN3rWt3fo93+HN0w5dyrQ9zNs9sE+bvJhczDxq2pMsyMHNmrJc0ana20F6yW9qrRjMyyzd3/JtoKMMzbFc3eTs4QHezRy+rnxastKMqC0Z4yH94hLe2p984/Lbwbcq4rds4C3d3JrZz0W94kQ+z4ps4jAOzAWu4M6tuTtt2KZL4yCuzCHO5Qy+2eQb31XO3v8941db5G/Muw0e1+kLkSRuzRg+5Yqtwsnc5tD95vHLzTit5RVe5+rtxGROxo+N4KsN5yDs3z/twBaO0LYN5UrOV0y+vP0L30vb6DQ+1Nhd3gD/fuIczd82benA3eOq/dtbHur37eSC/ukGDdrpXer0fbSoDuRPnumtDtFILNNjztqKbup7fc9lfuv8nevD/uUdrtexbuWMjr7CrepuHldY7r4R/eHJvuqTbefN7ugWbdzbbuQdDdT17empPs4LTuBnrtuULtEbrtk6vOyYru2a/ux6bpzkBZ4E96e4fLqE7d66fpQ7/FDdnqqETs7bCqFL7e2JTcyZdO9gPu0A79T8fuAtTtWM/K3uNvDnu7cGH80R6uwKL7yorZUZn+iDrcuzPdwgH/KdqZ8kz+e+6th0ztbmrvJe/seYycbY/p+SHu8vbPJeCvHWhPH4Xum+nNkE/zvRVfzX+h7wQ+/wKp3lvwztEy7mu8vyaM7inP7fm75Xto7u8L7cUc7hlTzrZ4vYht7yas6/YD/Pajyzf6vuCf/0E/yKZ8/tl+7r/u7xMk/uH//y7v7g0732p972kfvJZM/s9S745p3O64314V72RJziGkzzA2zWXd7w2f74+Uz3MD/zEs/2iL74kF73EB7KiV/4OT75Ry/3tU7wi86Mdk/hjA/5Zr/6+x738l7yWU+ho5/y7Z7gEZ7TPu6npi/jC4r0TA/u7076vA78or/Ish78px/nH9zKB7/wai/geG/59D74NJ/nm+/8rO/J8w7Vzz/u9Gzq4P/jCn39Ha+8o//v2S4l5Osv7cc+4tQe8+Vv/PPf+C6f/edO3Uue/J5fx22M91wf+7fP//Jf4mHOz2us4sTv9yCd+cNf4q1O8MOv86B//MEe5L3exlNP/c2/9T/O9UYP0iXe6lwv+UDP/P4f6SDM9dRf41Lf6W0c2qYc4cDe57M90o6P2a7M/nvf9F+f99l9//4/5/xP/tp//JFtyhEO7H0+2yPt+Jjtyuy/903/9Xmf3ffv/3PO/+Sv/ccf2aYc4cDe57M90o6P2a7M/nvf9F+f99l9//4/5/xP/tp//JFtyhEO7H0+2yPt+Jjtyuy/903/9Xmf3ffv/3PO/+Sv/cd/7YvNoO8/uX//bvR3v+euPPz4jfqdK+pGn8zub7Spz+5h+v7EjvIXP/5jOvyjXex7vuN879uxndd0bbRUL8liXftB/uPvT+wof/HjP6bDP9rFvuc7zve+Hdt5TddGS/WSLNa1H+Q//v7EjvIXP/5jOvyjXex7vuN879uxndd0bbRUL8liXftB/uPvT+wof/HjP6bDP9rFvuc7zve+vetaP/15L96YOdKOL/xXH+HFPv6tX+yeT+z6z9aw/8o3H+HFPv6tX+yeT+z6z9aw/8o3H+HFPv6tX+yeT+z6z9aw/8o3H+HFPv6tX+yeT+z6z9aw/8o3H+HFPv6tX+yeT+z6z9aw/8o3H+HF/z7+rV/snk/s+s/WsP/KNx/hxT7+rV/snk/s+s/WsP/KNx/hxT7+rV/snk/s+s/WsP/KNx/hrP70xD3n/P/oNz/4cs78v++69i/W0h/koP/K9Q+u3S/+tO7ZOa/6vg/6r1z/4Nr94k/rnp3zqu/7oP/K9Q+u3S/+tO7ZOa/6vg/6r1z/4Nr94k/rnp3zqu/7oP/K9Q+u3S/+tO7ZOa/6vg/6r1z/4Nr94k/rnp3zqu/7oP/K9Q+u3S/+tO7ZOa/6vg/6r1z/4Nr94k/r+S/9oI7sev/o9U/XRivslhzsPE/dAr/8FI/9hEz88a+oXt/v3G/9LBv6ea/8dUz8fy/8+f8eyfhv+E3u9Miu949e/3RttMJuycHO89Qt8MtP8dhPyMQf/4rq9f3O/dbPsqGf98pfx8T/98Kf75GM/4bf5E6P7Hr/6PVP10Yr7JYc7DxP3QK//BSP/YRM/PGvqF7f79xv/Swb+nmv/HVM/H8v/Pkeyfjf9ZEM9Q+v/E9f/6q/7p7f98nM6pY99rK/xVKO66Pt2b+/7mlv2WMv+1ss5bg+2p79++ue9pY99rK/xVKO66Pt2b+/7mlv2WMv+1ss5bg+2p79++ue9pY99rK/xVKO66Pt2b+/7mlv2WMv+1ss5bg+2p79++ue9pY99rK/xVKO66Pt2b+/7mlv2WMv+1v/LOW4Ptqe/fvr3vN9H/2dqvtoH/mv/P6mLPygn/d9jvIqXvmNH9bXDPTKz8/jrOE9z8/9z/vdHdqSjM3CD/p53+cor+KV3/hhfc1Ar/z8PM4a3vP83P+8392hLcnYLPygn/d9jvIqXvmNH9bXDPTKz8/jrOE9z8/9z/vdHdqSjM3CD/p53+cor+KV3/hhfc1Ar/z8PM4a3vP83P+8392hLcnYLPygn/fGfvOoT/15L96AH/bbf8APbMm1b/RvjfIqzpzTT/jbf8APbMm1b/RvjfIqzpzTT/jbf8APbMm1b/RvjfIqzpzTT/jbf8APbMm1b/RvjfIqzpzTT/jbf8AP/2zJtW/0b43yKs6c00/423/AD2zJtW/0b43yKs6c00/423/AD2zJtW/0b43yKs6c00/423/AD2zJtW/0b43yKs6c00/423/AD2zJtU/9Ol388X/WGY7yf27f/a/zTe70vi2jZ53hKP/n9t3/Ot/kTu/bMnrWGY7yf27f/a/zTe70vi2jZ53hKP/n9t3/Ot/kTu/bMnrWGY7yf27f/a/zTe70vi2jZ53hKP/n9t3/Ot/kTu/bMnrWGY7yf27f/a/zTe70vi2jZ53hKP/n9t3/Ot/kTu/bMnrWGY7yf27f/a/zKn/yA+76cA+7br/jxfz+ktzZv575CLryAJ3++e/kMv9a8dIf+7kf8VIfxRfu8tkvyZ3965mPoCsP0Omf/04uoxUv/bGf+xEv9VF84S6f/ZLc2b+e+Qi68gCd/vnv5DJa8dIf+7kf8VIfxRfu8tkvyZ3965mPoCsP0Omf/04uoxUv/bGf+xEv9VF84S6f/ZKslPsN9YJt/5T/510/2yNdzDcP6Gze64ev4sTt3a9N9U9/84DO5r1++CpO3N792lT/9DcP6Gze64ev4sTt3a9N9U9/84DO5r1++CpO3N792lT/9DcP6Gze64ev4sTt3a9N9U9/84DO5r1++CpO3N792lT/9DcP6Gze64ev4sTt3a9N9U9/84DO5r1++CpO3N7//dpU//SnrfHC/+NcL/my/er5TeWH7uqhPfcerc2cb/G5v/yRrP7gyrHYjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37DfWmjOLr7v20r/yCje35f+37rf5d3/XpP/xKP/fYruE9P7ks2/ViJeW4rtzC3vPnTutSjutdH/h43uuiHuTtH+M7j8hd3/Ub/+eS//co7roxvvOI3PVdv/F/Lvl/j+KuG+M7j8hd3/Ub/+eS///3KO66Mb7ziNz1Xb/xfy75f4/irhvjO4/IXd/1G//nkv/3KO66Mb7ziNz1Xb/xfy75f4/irhvjO0/4Rt/6xL3fKp/zc677QO/gkV/w90/c+63yOT/nug/0Dh75BX//xL3fKp/zc677QO/gkV/w90/c+63yOT/nug/0Dh75BX//xL3fKp/zc677QO/gkV/w90/c+63yOT/nug/0Dh75BX//xL3fKp/zc677QO/gkV/w90/c+63yOT/nug/0Dh75BX//xL3fKp/zc677QO/gXV/jDLrzNZ772p/3xs710E/IXX/5W7zzNZ772p/3xs710E/IXX/5W7zzNZ772p/3xv/O9dBPyF1/+Vu88zWe+9qf98bO9dBPyF1/+Vu88zWe+9qf98bO9dBPyF1/+Vu88zWe+9qf98bO9dBPyF1/+Vu88zWe+9qf98bO9dBPyF1/+Vu88zWe+9qf98bO9dBPyF1/+Vu88zWe+9qf98bO9dAf1mh954Tc21xf6Mmc/4t94Zgf2W7b/Vsv1z/c2wSf0zkf6el/82LP0EKt4qfd/iX++ReO+ZHttt2/9XL9w71N8Dmd85Ge/jcv9gwt1Cp+2u1f4p9/4Zgf2W7b/Vsv1z/c2wSf0zkf6el/82LP0EKt4qfd/iX++ReO+ZHttt2/9XL9w71N8Dmd85Ge/jcv9gz/LdQqftrtX+Kff+GYH+yh7+pOr3jUbebib/UXTsjdT90C//no//PZ3/fRr/xzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/3EjvIsC/1OfN7arPxzj+SKn/2W/eM73/wbL9dN//WuHtpCj7lyXec73/wbL9dN//WuHtpCj7lyXec73/wbL9dN//WuHtpCj7lyXec73/wbL9dN//WuHtpCj7lyXec73/wb/y/XTf/1rh7aQo+5cl3nO9/8Gy/XTf/1rh7aQo+5cl3nO9/8Gy/XTf/1rh7aQo+5cl3nO9/8Gy/XTf/1rh7aQo+5cl3nO9/8Gy/XTf/1rh7aQo+5cl3nu45UT536U9+5p63iaf70BP/6YvXUqT/1nXvaKp7mT0/wry9WT536U9+5p63iaf70BP/6YvXUqT/1nXvaKp7mT0/wry9WT536U9+5p63iaf70BP/6YvXUqT/1nXvaKp7mT0/wry9WT536U9+5p63iaf70BP/6YvXUqT/1nXvaKp7mT0/wry9WT536U9+5p63iaf70BH/zYm/84T/4ns31PA/4Wy/Xdb7zC//994Ae5vx88rVP/Xkvqzm/2GIt/UHe/tbf5Oye/F/P2X+P2Xf/9TV+4YTs5+Ps/k0u1D++8wv994Ae5vx88rVP/Xkvqzm/2GIt/UHe/tbf5Oye/F/P2X+P2Xf/9TV+4YTs5+Ps/k0u1D++8wv994Ae5vx88rVP/Xkvqzm/2GIt/UHe/tbf5Oye/F/f/JGf/r39/m6r4RQ/+64+9Yls/qgP/XUuo4oKwk898YpfzJWP44ee/r39/m6r4RQ/+64+9Yls/qgP/XUuo4oKwk898YpfzJWP44ee/r39/m6r4RQ/+64+9Yls/qgP/XUuo4oKwk898YpfzJWP44ee/r39/m7Tq+EUP/uuPvWJbP6oD/11LqOKCsJPPfGKX8yVj+OHnv69/f5uq+EUP/uuPvWJLNdx2unF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//aX/AX3+nF7v3Q/8M2/+fQv//avwEFAAA7" width="220" height="220"></qr-code>
									<manual-box>
										<!---->



										<!----><div ngxclipboard="">
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

									<div class="payment__details__instruction__open-wallet">
										<a class="payment__details__instruction__open-wallet__btn action-button" href="bitcoin:?r=https://bitpay.com/i/9ssav4zPhdUGocEzHpkgQR">
											<span i18n="">Open in wallet</span>
											<img class="payment__details__instruction__open-wallet__btn__external-link" src="assets/images/external-link.svg">
										</a>
									</div>
								</div>
								<!----><div class="manual__step-one__instructions manual__step-one__instructions--how-to-pay">
									<a class="item-highlighter item-highlighter--large item-highlighter--secondary" href="https://support.bitpay.com/hc/en-us/articles/115005559826" target="_blank">
										<span i18n="">How do I pay this?</span>
									</a>
								</div>

							</div>

						</div>

						<div class="bp-view" id="emailAddressView">
							<contact-email-form><form class="manual__step-one refund-address-form contact-email-form ng-untouched ng-pristine ng-invalid" id="emailAddressForm" name="emailAddressForm" novalidate="">

									<div class="contact-email-form__instructions">
										<div class="manual__step-one__header contact-email-form__instructions__header">
											<!----><span i18n="">Contact &amp; Refund Email</span>
											<!---->
										</div>

										<!----><div class="manual__step-one__instructions">
            <span class="initial-label">
                    <!----><p i18n="">By giving my email address, I give explicit consent to BitPay to use it to contact me about payment issues. </p>
                    <!----><span i18n=""> <a href="https://bitpay.com/about/privacy" target="_blank">View Privacy Policy</a></span>
                <!----><bp-truncate>

		            <bp-truncate-more><!----></bp-truncate-more>

	            </bp-truncate>

                <!---->
            </span>
											<span class="submission-error-label" i18n="">Please enter a valid email address.</span>
										</div>

										<!---->
									</div>

									<div class="contact-email-form__form">
										<div class="input-wrapper">
											<input bp-focus="" class="bp-input email-input ng-untouched ng-pristine ng-invalid" i18n-placeholder="" id="emailAddressFormInput" name="receiptEmail" placeholder="Your email" required="" type="email" pattern="/^(?:[\w\!\#\$\%\&amp;\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&amp;\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/">
											<bp-loading-button>
												<button class="action-button" style="margin-top: 15px;" type="submit">
      <span class="button-text">
                <!----><span i18n="">Continue</span>
                <!---->
            </span>
													<div class="loader-wrapper">
														<bp-spinner>
															<svg xml:space="preserve" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 50 50" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px">
    <path d="M11.1,29.6c-0.5-1.5-0.8-3-0.8-4.6c0-8.1,6.6-14.7,14.7-14.7S39.7,16.9,39.7,25c0,1.6-0.3,3.2-0.8,4.6l6.1,2c0.7-2.1,1.1-4.3,1.1-6.6c0-11.7-9.5-21.2-21.2-21.2S3.8,13.3,3.8,25c0,2.3,0.4,4.5,1.1,6.6L11.1,29.6z"></path>
  </svg>
														</bp-spinner>
													</div>
												</button>
											</bp-loading-button>
										</div>

										<!---->
									</div>

								</form>
							</contact-email-form>
						</div>

						<div class="bp-view" id="link-expired" style="padding-top: 3.6rem;">
							<div class="manual__step-one refund-address-form">
								<div class="manual__step-one__header" i18n="">Link Expired</div>

								<div class="manual__step-one__instructions">
									<span i18n="">Sorry, this link has expired. Please try requesting another refund by clicking the button below.</span>
								</div>

								<div class="input-wrapper">
									<bp-loading-button i18n="">
										<button class="action-button" style="margin-top: 15px;" type="submit">
											<span class="button-text">Request Refund</span>
											<div class="loader-wrapper">
												<bp-spinner>
													<svg xml:space="preserve" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 50 50" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px">
    <path d="M11.1,29.6c-0.5-1.5-0.8-3-0.8-4.6c0-8.1,6.6-14.7,14.7-14.7S39.7,16.9,39.7,25c0,1.6-0.3,3.2-0.8,4.6l6.1,2c0.7-2.1,1.1-4.3,1.1-6.6c0-11.7-9.5-21.2-21.2-21.2S3.8,13.3,3.8,25c0,2.3,0.4,4.5,1.1,6.6L11.1,29.6z"></path>
  </svg>
												</bp-spinner>
											</div>
										</button>
									</bp-loading-button>
								</div>
							</div>
						</div>

						<div class="bp-view" id="fully-paid-refund-link-expired">
							<div class="manual__step-one refund-address-form">
								<div class="manual__step-one__header" i18n="">Link Expired</div>

								<div class="manual__step-one__instructions">
									<span i18n="">Sorry, this refund link has expired. Please contact the merchant to request another refund link for this payment.</span>
								</div>

								<div class="input-wrapper">
									<bp-loading-button i18n="">
										<button class="action-button" style="margin-top: 15px;" type="submit">
											<span class="button-text">Done</span>
											<div class="loader-wrapper">
												<bp-spinner>
													<svg xml:space="preserve" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 50 50" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px">
    <path d="M11.1,29.6c-0.5-1.5-0.8-3-0.8-4.6c0-8.1,6.6-14.7,14.7-14.7S39.7,16.9,39.7,25c0,1.6-0.3,3.2-0.8,4.6l6.1,2c0.7-2.1,1.1-4.3,1.1-6.6c0-11.7-9.5-21.2-21.2-21.2S3.8,13.3,3.8,25c0,2.3,0.4,4.5,1.1,6.6L11.1,29.6z"></path>
  </svg>
												</bp-spinner>
											</div>
										</button>
									</bp-loading-button>
								</div>
							</div>
						</div>

						<div class="bp-view" id="network-disruption">
							<div class="manual__step-one__header">
								<div class="warning--network-disruption__img">
									<img src="assets/images/alert-circle-exc.svg">
								</div>
								<span class="warning--network-disruption__header">Bitcoin <span i18n="">Unavailable</span></span>
							</div>

							<div class="manual__step-one__instructions">
								<span class="initial-label" i18n="">Due to current Bitcoin network conditions, BTC payments are temporarily unavailable. You can choose to use Bitcoin Cash (BCH) to complete your payment.</span>
							</div>

							<button class="action-button" id="disruption-continue">
								<span i18n="">Continue</span>
							</button>


						</div>

						<div class="bp-view wrong-email-view" id="compromised-invoice">
							<div class="manual__step-one refund-address-form" novalidate="" style="margin-top: -1rem;">
								<div class="manual__step-one__header">
									<span i18n="">There seems to be a problem</span>
								</div>

								<div class="manual__step-one__instructions">
									<span class="initial-label" i18n="">This invoice was previously opened, and the address <strong>ana@widgilabs.com</strong> was submitted as your contact email. If you entered this email, you can still safely make your payment. <br> <br> If you did not submit the email address, it's possible a thief falsely submitted this address to steal refunds. Please contact the merchant about this security incident, and try your payment again.</span>
								</div>

								<div class="input-wrapper">
									<a class="action-button" style="margin-top: 15px;" target="_blank" href="mailto:support@giftoff.com">
										<span i18n="">Contact Gift Off</span>
									</a>
								</div>
								<div class="refund-address-form__link">
									<span i18n="">I understand, continue to payment&nbsp;→</span>
								</div>
							</div>
						</div>

						<div class="bp-view confirm-bitcoin-address-view" id="confirm-refund-address">
							<form class="manual__step-one refund-address-form ng-untouched ng-pristine ng-valid" novalidate="" style="padding-top: 1.6rem;">
								<div><img src="assets/images/mail.svg"></div>
								<div class="manual__step-one__header">
									<span i18n="">Please confirm your address</span>
								</div>

								<div class="manual__step-one__instructions">
                    <span class="initial-label" i18n="">You should receive an email from BitPay in a moment at <strong>ana@widgilabs.com</strong>. To ensure your refund is sent to the correct address, please confirm your <currency-name>
		                    <!----><span style="text-transform: lowercase;">
            Bitcoin
        </span>
	                    </currency-name> address by clicking the link in the email. </span>
								</div>

								<bp-resend-link id="resend-link"><div class="bp-resend__link">
  <span class="link-text">
    <!----><span i18n="">Resend email</span>
    <!---->
  </span>
										<div class="success-text">
											<!----><img src="assets/images/circle-check.svg">
											<!----><div i18n="">Email resent</div>
											<!---->
										</div>
									</div>
								</bp-resend-link>
							</form>
						</div>

						<div class="bp-view refund-address-view" id="enter-refund-address">

							<form class="manual__step-one refund-address-form ng-untouched ng-pristine ng-invalid" name="refundAddressForm" novalidate="" style="margin-top: 28px; margin-bottom: 4rem;">
								<div class="manual__step-one__header">
									<!----><span i18n="">Please provide a refund address.</span>
									<!---->
								</div>
								<div class="manual__step-one__instructions">
                    <span class="initial-label">
                      <!----><span i18n="">To send your refund of 0.00000N BTC, we’ll need a <currency-name>
			                    <!----><span style="text-transform: lowercase;">
            Bitcoin
        </span>
		                    </currency-name> address from your wallet. Please open your <currency-name>
			                    <!----><span style="text-transform: lowercase;">
            Bitcoin
        </span>
		                    </currency-name> wallet, copy a receiving address, and paste it below. </span>
                    </span>
                    <span class="submission-error-label">
                        <!---->
                    </span>
								</div>

								<div class="input-wrapper">
									<!----><bp-refund-address name="refundAddress" ngmodel="" class="ng-untouched ng-pristine ng-invalid"><div class="bp-refund-address">
											<div class="bitcoin-logo">
												<div><img src="assets/images/bitcoin-symbol.svg"></div>
											</div>
											<input class="bp-input {'not-empty': addressValue.length > 0} ng-untouched ng-pristine ng-valid" id="refund-address-input" name="refundAddress" ngclass="{'not-empty': addressValue.length > 0}">
										</div>
									</bp-refund-address>
									<bp-loading-button i18n="" id="request-refund-button">
										<button class="action-button" style="margin-top: 15px;" type="submit">
											<span class="button-text">Request Refund</span>
											<div class="loader-wrapper">
												<bp-spinner>
													<svg xml:space="preserve" style="enable-background:new 0 0 50 50;" version="1.1" viewBox="0 0 50 50" x="0px" xmlns="http://www.w3.org/2000/svg" y="0px">
    <path d="M11.1,29.6c-0.5-1.5-0.8-3-0.8-4.6c0-8.1,6.6-14.7,14.7-14.7S39.7,16.9,39.7,25c0,1.6-0.3,3.2-0.8,4.6l6.1,2c0.7-2.1,1.1-4.3,1.1-6.6c0-11.7-9.5-21.2-21.2-21.2S3.8,13.3,3.8,25c0,2.3,0.4,4.5,1.1,6.6L11.1,29.6z"></path>
  </svg>
												</bp-spinner>
											</div>
										</button>
									</bp-loading-button>
								</div>
								<div class="refund-address-form__cancel">
									<span i18n="">Cancel</span>
								</div>
							</form>
						</div>

						<div class="bp-view payment manual-flow" id="copy">

							<div class="manual__step-two__instructions">
								<!---->
								<div i18n="" style="margin-top: -10px;">To complete your payment, please copy the payment address below and send 0.002005 BTC from a <a href="https://support.bitpay.com/hc/en-us/articles/115005701523-Which-wallets-work-for-a-BitPay-payment-Which-wallets-are-compatible-" target="_blank">payment protocol-enabled wallet</a>.</div>

								<!---->
							</div>

							<manual-box>
								<!----><div class="manual-box flipped" style="margin-bottom: 30px;">
									<div class="manual-box__amount">
										<div class="manual-box__amount__label label">
            <span class="initial-label">
                <manual-box-amount-initial-label i18n="">Amount</manual-box-amount-initial-label>
            </span>
            <span class="final-label">

            </span>
										</div>
										<!----><div class="manual-box__amount__value copy-cursor" ngxclipboard="">
											<span>0.002005 BTC</span>
											<div class="copied-label">
												<span i18n="">Copied</span>
											</div>
										</div>
										<!---->
									</div>
									<div class="manual-box__address">
										<div class="flipper flipper__flipped-initially">
											<div class="flipper__back"></div>
											<div class="flipper__front">
												<div class="manual-box__address__arrow"></div>
												<div class="manual-box__address__label label">
													<manual-box-address-label i18n="">Address</manual-box-address-label>
												</div>
												<!----><div class="manual-box__address__value copy-cursor" ngxclipboard="">
													<div class="manual-box__address__wrapper">
														<div class="manual-box__address__wrapper__logo">
															<!----><img src="assets/images/lock.svg">
															<!---->
														</div>
														<div class="manual-box__address__wrapper__value trim">
															bitcoin:?r=https://bitpay.com/i/9ssav4zPhdUGocEzHpkgQR
														</div>
													</div>
													<div class="copied-label">
														<span i18n="">Copied</span>
													</div>
												</div>
												<!---->
											</div>
										</div>
									</div>
								</div>



								<!---->
							</manual-box>

							<!----><div class="manual__step-one__instructions">
								<a class="item-highlighter item-highlighter--large" href="https://support.bitpay.com/hc/en-us/articles/115005559826" target="_blank">
									<span i18n="">How do I pay this?</span>
								</a>
							</div>

						</div>

						<!---->

						<div class="bp-view pad" id="paid">
							<div class="status-block">
								<div class="success-block">
									<div class="status-icon">
										<div class="status-icon__wrapper">
											<div class="inner-wrapper">
												<div class="status-icon__wrapper__icon">
													<img src="assets/images/checkmark.svg">
												</div>
												<div class="status-icon__wrapper__outline"></div>
											</div>
										</div>
									</div>

									<!----><div class="success-message" i18n="">This invoice has been paid.</div>
									<!---->
								</div>
							</div>

							<!---->

							<div class="button-wrapper refund-address-form-container" id="refund-overpayment-button">
								<!---->
							</div>

							<!---->
						</div>

						<div class="bp-view" id="refund-pending">
							<div class="status-block">
								<div class="pending-block" style="position: relative; padding-bottom: 1.6rem;">
									<img src="assets/images/refund-pending.svg">
									<div class="pending-block__header" i18n="">Processing Refund</div>
									<span class="pending-block__message" i18n="">The amount below will be refunded to you within 1-2 business days. </span>
								</div>
							</div>

							<manual-box>
								<!----><div class="manual-box flipped" style="margin-bottom: 30px;">
									<div class="manual-box__amount">
										<div class="manual-box__amount__label label">
            <span class="initial-label">
                <manual-box-amount-initial-label>&nbsp;</manual-box-amount-initial-label>
            </span>
            <span class="final-label">
                <manual-box-amount-final-label i18n="">Amount To Be Refunded</manual-box-amount-final-label>
            </span>
										</div>
										<!---->
										<!----><div class="manual-box__amount__value">
            <span>
                <span bp-selectable="">0.00000N</span> BTC
            </span>
											<div class="copied-label">
												<span i18n="">Copied</span>
											</div>
										</div>
									</div>
									<div class="manual-box__address">
										<div class="flipper">
											<div class="flipper__back"></div>
											<div class="flipper__front">
												<div class="manual-box__address__arrow"></div>
												<div class="manual-box__address__label label">
													<manual-box-address-label i18n="">Will Be Refunded To</manual-box-address-label>
												</div>
												<!---->
												<!----><div class="manual-box__address__value">
													<div class="manual-box__address__wrapper">
														<div class="manual-box__address__wrapper__logo">
															<bitcoin-svg>
																<svg xmlns:xlink="http://www.w3.org/1999/xlink" height="13px" version="1.1" viewBox="0 0 10 13" width="10px" xmlns="http://www.w3.org/2000/svg">
																	<title>Bitcoin_Symbol</title>
																	<defs></defs>
																	<g fill="none" fill-rule="evenodd" id="Page-1" opacity="0.8" stroke="none" stroke-width="1">
																		<g fill="#5D5D5D" id="Manual-payment" transform="translate(-296.000000, -517.000000)">
																			<g id="Invoice" transform="translate(236.000000, 86.000000)">
																				<g id="Manual-info" transform="translate(31.000000, 282.000000)">
																					<g id="From-Copy-2" transform="translate(19.000000, 137.000000)">
																						<g id="Group-Copy-3">
																							<path d="M16.8269795,14.5288175 L17.2432895,12.7813644 L16.1835913,12.5249446 L15.7798967,14.2344097 C15.4992028,14.1679305 15.2153551,14.1014513 14.9346612,14.0444691 L15.3383558,12.3350041 L14.2660421,12.0785843 L13.8497321,13.8260375 C13.6226539,13.772221 13.3924219,13.7310672 13.1653437,13.6772507 L11.7051048,13.3258607 L11.4370263,14.456007 C11.4370263,14.456007 12.2286461,14.6301192 12.2002614,14.6459475 C12.6291868,14.7535805 12.7111873,15.0353257 12.695418,15.2505916 L12.2254923,17.2418018 C12.253877,17.2544645 12.2917234,17.2544645 12.3327236,17.2829556 C12.2917234,17.2702929 12.2664925,17.2702929 12.2254923,17.2576302 L11.5821041,20.0434251 C11.5284884,20.1763835 11.3928722,20.366324 11.0995629,20.2998448 C11.1121784,20.3125075 10.3205586,20.1099043 10.3205586,20.1099043 L9.81909423,21.3350209 L11.2004866,21.6579198 C11.4559495,21.724399 11.7114125,21.7782155 11.9511061,21.8478604 L11.5347961,23.6111419 L12.5944943,23.8675616 L13.0108043,22.1201085 C13.3072675,22.1992504 13.5879614,22.2688953 13.8560398,22.3353745 L13.4397298,24.0701649 L14.499428,24.3265847 L14.915738,22.5633031 C16.7260558,22.8862021 18.0790634,22.7374153 18.6278357,21.0817668 C19.0693766,19.7616799 18.5868354,19.008249 17.6217531,18.5112379 C18.3282186,18.3497884 18.8391445,17.8907654 18.9716068,16.9505596 C19.1450693,15.6589638 18.1673716,14.9846749 16.8269795,14.5288175 Z M16.5872858,20.3789867 C16.2782072,21.6990736 14.0547332,21.0121219 13.34196,20.8380097 L13.8907323,18.4954095 C14.616121,18.6726874 16.9089799,18.9955863 16.5872858,20.3789867 L16.5872858,20.3789867 Z M16.8805952,16.9505596 C16.5999013,18.1471852 14.7485833,17.5710321 14.1461953,17.4222454 L14.6571212,15.2980768 C15.2468937,15.4436979 17.1770584,15.7001176 16.8805952,16.9505596 L16.8805952,16.9505596 Z" id="Bitcoin_Symbol" transform="translate(14.405328, 18.202584) rotate(-13.000000) translate(-14.405328, -18.202584) "></path>
																						</g>
																					</g>
																				</g>
																			</g>
																		</g>
																	</g>
																</svg>
															</bitcoin-svg>
														</div>
														<div bp-selectable="" class="manual-box__address__wrapper__value trim"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>



								<!---->
							</manual-box>
							<!----><div class="manual__step-one__instructions">
								<a class="item-highlighter" href="https://support.bitpay.com/hc/en-us/articles/115003244163-Why-is-my-refund-less-than-I-paid-" target="_blank">
									<span i18n="">Why is my refund less than I paid?</span>
								</a>
							</div>
							<!---->
						</div>

						<div class="bp-view expired" id="low-fee">
							<div class="expired__body">

								<div class="expired__header" i18n="" style="font-weight: 400; font-size: 22px;">Payment Confirming</div>

								<div class="expired__text" i18n="">This payment was made with a low <a href="https://bitcoin.org/en/glossary/transaction-fee" target="_blank">bitcoin miner fee</a>, which may prevent it from being accepted by the Bitcoin network.</div>

								<div class="expired__text" i18n="">This is an issue with the configuration of your <currency-name>
										<!----><span style="text-transform: lowercase;">
            Bitcoin
        </span>
									</currency-name> wallet.</div>

								<div class="expired__text" i18n="">If the transaction doesn't confirm, the funds will be spendable again in your wallet. Depending on the wallet, this may take 48-72 hours.</div>

								<low-fee-timeline>
									<div class="timeline">
										<div class="timeline__item">
											<div class="timeline__item__icon timeline__item__icon--complete">
												<img src="assets/images/checkmark-small.svg">
											</div>
											<div class="timeline__item__name" i18n="">Transaction created</div>
										</div>
										<div class="timeline__item">
											<div class="timeline__item__icon timeline__item__icon--pending">
												<img src="assets/images/pending.svg">
											</div>
											<div class="timeline__item__name">
												<span i18n="">Transaction confirming — funds have not yet moved</span>
											</div>
										</div>
										<div class="timeline__item">
											<div class="timeline__item__icon"></div>
											<div class="timeline__item__name" i18n="">Payment received by Gift Off</div>
										</div>
									</div>
								</low-fee-timeline>

							</div>

							<!---->

							<!----><button class="action-button" style="margin-top: .75rem;">
								<bp-done-text><!----><span i18n="">Done</span>
									<!---->
								</bp-done-text>
							</button>
						</div>

						<div class="bp-view expired" id="expired">
							<!----><div>
								<!----><div>

									<div class="expired__body">

										<div class="expired__header" i18n="">What happened?</div>

										<!----><div>
											<div class="expired__text" i18n="">This invoice has expired. An invoice is only valid for 15 minutes. You can <div class="expired__text__link">return to Gift Off</div> if you would like to submit your payment again.</div>
											<div class="expired__text" i18n="">If you tried to send a payment, it has not yet been accepted by the Bitcoin network. We have not yet received your funds.</div>
											<div class="expired__text" i18n="">If the transaction is not accepted by the Bitcoin network, the funds will be spendable again in your wallet. Depending on your wallet, this may take 48-72 hours.</div>
										</div>

										<!---->

										<div class="expired__text">
											<span class="expired__text__bullet" i18n="">Invoice ID:</span> 9ssav4zPhdUGocEzHpkgQR<br>
											<!----><span>
                          <span class="expired__text__bullet" i18n="">Order ID:</span> 5b4c883ec2cf1
                        </span>
										</div>

									</div>

									<button class="action-button" style="margin-top: 20px;">
										<bp-done-text><!----><span i18n="">Done</span>
											<!---->
										</bp-done-text>
									</button>

								</div>

								<!---->

							</div>
							<!---->

							<!---->

						</div>

						<div class="bp-view expired" id="archived">

							<div class="expired-icon">
								<img src="assets/images/archived.svg">
							</div>

							<div class="archived__message">
								<div class="archived__message__header">
									<span i18n="">This invoice has been archived.</span>
								</div>
								<div>
									<span i18n="">Please contact the merchant for order information or assistance.</span>
								</div>
							</div>

						</div>

						<!----><div class="bp-view" id="refund-complete">
							<div class="status-block">
								<div class="success-block" style="opacity: 1;">
									<div class="status-icon">
										<div class="status-icon__wrapper">
											<div class="inner-wrapper">
												<div class="status-icon__wrapper__icon">
													<img src="assets/images/checkmark.svg">
												</div>
												<div class="status-icon__wrapper__outline" style="height: 117px; width: 117px;"></div>
											</div>
										</div>
									</div>

									<div class="success-message">
										<!----><span>
                        <span i18n="">Refund Complete</span>
                      </span>
										<!---->
									</div>
								</div>
							</div>


							<manual-box>
								<!----><div class="manual-box flipped" style="margin-bottom: 30px;">
									<div class="manual-box__amount">
										<div class="manual-box__amount__label label to-final-label">
            <span class="initial-label">
                <manual-box-amount-initial-label>&nbsp;</manual-box-amount-initial-label>
            </span>
            <span class="final-label">
                <manual-box-amount-final-label>
	                <!----><span i18n="">Amount Refunded</span>
	                <!---->
                </manual-box-amount-final-label>
            </span>
										</div>
										<!---->
										<!----><div class="manual-box__amount__value">
            <span>
                <span bp-selectable="">0.00000N</span> BTC
            </span>
											<div class="copied-label">
												<span i18n="">Copied</span>
											</div>
										</div>
									</div>
									<div class="manual-box__address">
										<div class="flipper flipper__flip flipper__flipped-initially">
											<div class="flipper__back"></div>
											<div class="flipper__front">
												<div class="manual-box__address__arrow"></div>
												<div class="manual-box__address__label label">
													<manual-box-address-label i18n="">Refunded To</manual-box-address-label>
												</div>
												<!---->
												<!----><div class="manual-box__address__value">
													<div class="manual-box__address__wrapper">
														<div class="manual-box__address__wrapper__logo">
															<bitcoin-svg>
																<svg xmlns:xlink="http://www.w3.org/1999/xlink" height="13px" version="1.1" viewBox="0 0 10 13" width="10px" xmlns="http://www.w3.org/2000/svg">
																	<title>Bitcoin_Symbol</title>
																	<defs></defs>
																	<g fill="none" fill-rule="evenodd" id="Page-1" opacity="0.8" stroke="none" stroke-width="1">
																		<g fill="#5D5D5D" id="Manual-payment" transform="translate(-296.000000, -517.000000)">
																			<g id="Invoice" transform="translate(236.000000, 86.000000)">
																				<g id="Manual-info" transform="translate(31.000000, 282.000000)">
																					<g id="From-Copy-2" transform="translate(19.000000, 137.000000)">
																						<g id="Group-Copy-3">
																							<path d="M16.8269795,14.5288175 L17.2432895,12.7813644 L16.1835913,12.5249446 L15.7798967,14.2344097 C15.4992028,14.1679305 15.2153551,14.1014513 14.9346612,14.0444691 L15.3383558,12.3350041 L14.2660421,12.0785843 L13.8497321,13.8260375 C13.6226539,13.772221 13.3924219,13.7310672 13.1653437,13.6772507 L11.7051048,13.3258607 L11.4370263,14.456007 C11.4370263,14.456007 12.2286461,14.6301192 12.2002614,14.6459475 C12.6291868,14.7535805 12.7111873,15.0353257 12.695418,15.2505916 L12.2254923,17.2418018 C12.253877,17.2544645 12.2917234,17.2544645 12.3327236,17.2829556 C12.2917234,17.2702929 12.2664925,17.2702929 12.2254923,17.2576302 L11.5821041,20.0434251 C11.5284884,20.1763835 11.3928722,20.366324 11.0995629,20.2998448 C11.1121784,20.3125075 10.3205586,20.1099043 10.3205586,20.1099043 L9.81909423,21.3350209 L11.2004866,21.6579198 C11.4559495,21.724399 11.7114125,21.7782155 11.9511061,21.8478604 L11.5347961,23.6111419 L12.5944943,23.8675616 L13.0108043,22.1201085 C13.3072675,22.1992504 13.5879614,22.2688953 13.8560398,22.3353745 L13.4397298,24.0701649 L14.499428,24.3265847 L14.915738,22.5633031 C16.7260558,22.8862021 18.0790634,22.7374153 18.6278357,21.0817668 C19.0693766,19.7616799 18.5868354,19.008249 17.6217531,18.5112379 C18.3282186,18.3497884 18.8391445,17.8907654 18.9716068,16.9505596 C19.1450693,15.6589638 18.1673716,14.9846749 16.8269795,14.5288175 Z M16.5872858,20.3789867 C16.2782072,21.6990736 14.0547332,21.0121219 13.34196,20.8380097 L13.8907323,18.4954095 C14.616121,18.6726874 16.9089799,18.9955863 16.5872858,20.3789867 L16.5872858,20.3789867 Z M16.8805952,16.9505596 C16.5999013,18.1471852 14.7485833,17.5710321 14.1461953,17.4222454 L14.6571212,15.2980768 C15.2468937,15.4436979 17.1770584,15.7001176 16.8805952,16.9505596 L16.8805952,16.9505596 Z" id="Bitcoin_Symbol" transform="translate(14.405328, 18.202584) rotate(-13.000000) translate(-14.405328, -18.202584) "></path>
																						</g>
																					</g>
																				</g>
																			</g>
																		</g>
																	</g>
																</svg>
															</bitcoin-svg>
														</div>
														<div bp-selectable="" class="manual-box__address__wrapper__value trim"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>



								<!---->
							</manual-box>

							<button class="action-button finished" style="margin-top: 23px;">
								<bp-done-text><!----><span i18n="">Done</span>
									<!---->
								</bp-done-text>
							</button>
						</div>

						<!---->

						<!---->

						<div class="footer-button enter-different-address-button">
							<bp-done-text><!----><span i18n="">Done</span>
								<!---->
							</bp-done-text>
						</div>

					</div>

				</div>
			</div>
		</div>

		<div class="footer">
			<a class="footer__item how-to-pay no-border hidden" style="padding-right: 0;" target="_blank" href="/pay-with-bitcoin">
				<img class="how-to-pay__help-icon" src="assets/images/help.svg">
				<!---->
				<!----><span i18n="">How do I pay this?</span>
			</a>
		</div>
	</div>
</div>