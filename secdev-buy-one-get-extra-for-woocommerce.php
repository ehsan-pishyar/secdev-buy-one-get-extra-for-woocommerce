<?php

/*
 * Plugin Name:       SecDev Buy One Get Extra for WooCommerce
 * Plugin URI:        https://github.com/ehsan-pishyar/secdev-buy-one-get-extra-for-woocommerce
 * Description:       Automatically reward customers with free products when they purchase eligible items. Apply promotions to all products, selected categories, tags, or specific products.
 * Version:           1.0.0
 * Requires at least: 6.7
 * Tested up to:      7.1
 * Requires PHP:      8.1
 * Author:            Ehsan Pishyar
 * Author URI:        https://github.com/ehsan-pishyar
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       secdev-buy-one-get-extra-for-woocommerce
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 */

use SDBOGE\Core\SDBOGE_Plugin;

if (!defined('ABSPATH')) exit;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if (!defined('SDBOGE_URI')) {
	define('SDBOGE_URI', plugin_dir_url(__FILE__));
}

if (!defined('SDBOGE_PATH')) {
	define('SDBOGE_PATH', plugin_dir_path(__FILE__));
}

if (!defined('SDBOGE_FILE')) {
	define('SDBOGE_FILE', __FILE__);
}

if (!defined('SDBOGE_VERSION')) {
	define('SDBOGE_VERSION', '1.0');
}

// Activation hook
register_activation_hook( __FILE__, 'sdboge_activate' );
function sdboge_activate(): void {}

// Deactivation hook
register_deactivation_hook( __FILE__, 'sdboge_deactivate' );
function sdboge_deactivate(): void {
	flush_rewrite_rules();
}

add_action('init', 'sdboge_run_plugin' );
function sdboge_run_plugin(): void {
	SDBOGE_Plugin::instance();
}