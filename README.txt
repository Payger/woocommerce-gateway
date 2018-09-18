# Payger Woocommerce Gateway

Extends Woocommerce Gateways to accept payments with crypto currencies through Payger.

## Description

Payger Woocommerce Gateway is a FREE WordPress plugin by [Payger](https://payger.com).
Start accepting Bitcoins, Ethereum or other crypto currencies on Your Woocommerce online store.
Increase your sales as you add all major altcoins as payment option for your customers.

Key Features:

* Support all wallets that support payment protocol
* Price in your local currency, let customers pay with crypto currencies e.g. bitcoins.
* Shop owners can choose a subset of crypto currencies, to allow payments with, depending on the local currency.
* Customers can choose the currency they would like to pay with.
* Customers will get an estimate value prior to "place order" on the selected crypto currency.
* Orders will automatically update status when payment is detected, no manual validation needed.
* Handles underpaid orders, asking customer the missing amount if necessary.
* Complete checkout process happens within your website/theme


## Installation

This plugin requires WooCommerce and a Payger account. Please make sure you have WooCommerce installed and a Payger account created.

1. Go to [Payger](https://payger.com) and register as a Business.
2. On Payger Account get your API KEY (username) and  API Secret (password).
3. Upload `woocommerce-gateway-payger` to the `/wp-content/plugins/` directory
4. Activate the plugin through the 'Plugins' menu in WordPress
5. On the Dashboard go to WooCommerce > Settings > Checkout > Payger and do the following:
6. Enable payments through payger
7. Set your username and password previously given by Payger and save changes.
8. On the Advanced Options please choose the "Accepted Currencies" for your shop. ( This will be a list of crypto currencies based on your shop currency )

You are now ready to start accepting crypto currencies on your website.

## Frequently Asked Questions

= Do I need to have a Payger account? =

Yes. Before using the plugin you will need to register on [Payger](https://payger.com) as Business. This will
allow you to generate a Key and Secret pair that would than be needed to configure the plugin and start showing crypto currencies as a payment option.

= How will underpaid orders work? =

In case the payment is marked as underpaid, a new email with a new address is sent to the buyer so that he can complete the payment.
The order will still be On-hold until the payment isn't completed.

= Why is my order cancelled after 15 minutes without a payment? =

15 minutes is a Payger limit to finish a payment. Users will get a popup window after "Place Order" with a timer counter for 15 minutes.
On that popup there will be all the information to make the payment. The buyer must copy the address or scan the QrCode to process the payment.
If the buyer does not finish the payment within 15 minutes the order is cancelled. It's exactly the same behaviour as if you define 15 minutes as your hold stock time.
For that time the order will be "pending payment".

= What to do if the order expires? =

The buyer will need to select the products again and go to cart and checkout page.

= Is the stock restored if the order expires? =

Order gets cancelled but the stock is not restored. As WooCommerce does not restores stock on cancellation we also don't do it.
There are already some plugins to reduce stock upon cancellation.

The buyer will need to select the products again and go to cart and checkout page.
