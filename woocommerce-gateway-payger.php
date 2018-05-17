<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.widgilabs.com
 * @since             1.0.0
 * @package           Woocommerce_Gateway_Payger
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Gateway Payger
 * Plugin URI:        http://www.widgilabs.com
 * Description:       Payger Payment Gateway for Woocommerce
 * Version:           1.0.0
 * Author:            WidgiLabs
 * Author URI:        http://www.widgilabs.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-gateway-payger
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-payger-activator.php
 */
function activate_woocommerce_gateway_payger() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-payger-activator.php';
	Woocommerce_Payger_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-payger-deactivator.php
 */
function deactivate_woocommerce_gateway_payger() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-payger-deactivator.php';
	Woocommerce_Payger_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_gateway_payger' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_gateway_payger' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-payger.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function run_woocommerce_gateway_payger() {

	new Woocommerce_Payger();

}
run_woocommerce_gateway_payger();