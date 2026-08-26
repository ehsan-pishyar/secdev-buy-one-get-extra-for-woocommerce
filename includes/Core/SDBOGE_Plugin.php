<?php

namespace SDBOGE\Core;

use SDBOGE\Admin\SDBOGE_Menu;
use SDBOGE\Front\SDBOGE_Bootstrap;
use SDBOGE\Helpers\SDBOGE_RegisterSettings;

if (!defined('ABSPATH')) exit;

class SDBOGE_Plugin {
	const MINIMUM_PHP_VERSION = '5.6';

	private static ?self $instance = null;

	static function instance(): ?self {
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	function __construct() {
		if (!$this->sdboge_requirements_satisfied()) return;

		add_filter('plugin_action_links_' . plugin_basename(SDBOGE_FILE), [$this, 'sdboge_plugin_action_links']);
		$this->sdboge_register_admin_scripts();
		$this->sdboge_register_cart_page_scripts();
		$this->sdboge_register_menu();
		$this->sdboge_register_settings();
		$this->sdboge_apply_frontend();
	}

	/**
	 * @return bool
	 * Check requirements
	 */
	function sdboge_requirements_satisfied(): bool {
		if (!$this->sdboge_check_minimum_php_version()) return false;

		return true;
	}

	/**
	 * @return bool
	 * Check minimum PHP version
	 */
	function sdboge_check_minimum_php_version(): bool {
		// Check for a required PHP version
		if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
			add_action('admin_notices', [$this, 'sdboge_admin_notice_minimum_PHP_version' ]);
			return false;
		}

		return true;
	}

	/**
	 * @return void
	 * Register plugin page (admin) scripts
	 */
	function sdboge_register_admin_scripts(): void {
		$page = isset($_GET['page']) ? sanitize_key(wp_unslash($_GET['page'])) : '';

		if ($page !== 'sdboge-admin') return;

		add_action('admin_enqueue_scripts', [$this, 'sdboge_admin_styles' ]);
		add_action('admin_enqueue_scripts', [$this, 'sdboge_admin_scripts' ]);
	}

	/**
	 * @return void
	 * Register Cart page scripts
	 */
	function sdboge_register_cart_page_scripts(): void {
		add_action('wp_enqueue_scripts', [$this, 'sdboge_cart_page_styles' ]);
	}

	/**
	 * @return void
	 * Plugin page (admin) styles
	 */
	function sdboge_admin_styles(): void {
		wp_register_style( 'sdboge-tailwindcss-admin', SDBOGE_URI . 'src/output.css', [], '4.3');
		wp_register_style( 'sdboge-select2', SDBOGE_URI . 'assets/css/select2.min.css', [], '4.1.0');
		wp_enqueue_style( 'sdboge-tailwindcss-admin' );
		wp_enqueue_style( 'sdboge-select2' );
	}

	/**
	 * @return void
	 * Plugin page (admin) scripts
	 */
	function sdboge_admin_scripts(): void {
		wp_register_script('sdboge-select2', SDBOGE_URI . 'assets/js/select2.min.js', ['jquery'], '4.1.0', true);
		wp_register_script('sdboge-admin', SDBOGE_URI . 'assets/js/sdboge_admin.js', ['jquery'], '1.0.0', true);
        wp_register_script('sdboge-condition-tags', SDBOGE_URI . 'assets/js/sdboge_condition_tags.js', ['jquery'], '1.0.0', true);
		wp_enqueue_script('sdboge-select2');
		wp_enqueue_script('sdboge-admin');
		wp_enqueue_script('sdboge-condition-tags');
	}

	/**
	 * @return void
	 * Cart page styles
	 */
	function sdboge_cart_page_styles(): void {
		if (!is_cart()) return;

		wp_register_style( 'sdboge-tailwindcss-front', SDBOGE_URI . 'src/output.css', [], '4.3');
		wp_enqueue_style( 'sdboge-tailwindcss-front' );
	}

	/**
	 * @return void
	 * Register menu
	 */
	function sdboge_register_menu(): void {
		SDBOGE_Menu::instance();
	}

	/**
	 * @return void
	 * Register settings
	 */
	function sdboge_register_settings(): void {
		SDBOGE_RegisterSettings::sdboge_init();
	}

	/**
	 * @return void
	 * Apply front logic
	 */
	function sdboge_apply_frontend(): void {
		SDBOGE_Bootstrap::instance();
	}

	/**
	 * @param $links
	 *
	 * @return array
	 * Settings link in plugin activation page
	 */
	function sdboge_plugin_action_links($links): array {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url(admin_url('admin.php?page=sdboge-admin')),
			esc_html__('Settings', 'secdev-buy-one-get-extra-for-woocommerce')
		);

		array_unshift($links, $settings_link);

		return $links;
	}

	function sdboge_admin_notice_minimum_PHP_version(): void {
		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}

		$message = sprintf(
		/* translators: 1: Plugin name, 2: PHP, 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'secdev-buy-one-get-extra-for-woocommerce' ),
			'<strong>' . esc_html__('SecDev Buy One Get Extra for WooCommerce', 'secdev-buy-one-get-extra-for-woocommerce') . '</strong>',
			'<strong>' . esc_html__('PHP', 'secdev-buy-one-get-extra-for-woocommerce') . '</strong>',
			esc_html(self::MINIMUM_PHP_VERSION)
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post($message) );
	}
}