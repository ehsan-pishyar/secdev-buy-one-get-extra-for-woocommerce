<?php

namespace SDBOGE\Admin;

if (!defined('ABSPATH')) exit;

class SDBOGE_Menu {
	private static ?self $instance = null;

	static function instance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action('admin_menu', [$this, 'sdboge_register_admin_menu' ]);
	}

	function sdboge_register_admin_menu(): void {
		add_menu_page(
			esc_html__('SecDev - Buy One Get Extra', 'secdev-buy-one-get-extra-for-woocommerce'),
			esc_html__('SecDev - Buy One Get Extra', 'secdev-buy-one-get-extra-for-woocommerce'),
			'manage_options',
			'sdboge-admin',
			[$this, 'sdboge_load_admin_page' ],
			SDBOGE_URI . 'assets/imgs/secdev-favicon.webp',
			57
		);
	}

	function sdboge_load_admin_page(): void {
		if (!current_user_can('manage_options')) return;

		$template = trailingslashit(SDBOGE_PATH) . 'includes/Admin/Templates/SDBOGE_AdminPage.php';
		if (is_readable($template)) {
			include $template;
		}
	}
}
