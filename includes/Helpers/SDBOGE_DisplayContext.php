<?php

namespace SDBOGE\Helpers;

use SDBOGE\Admin\SDBOGE_Settings;

if (!defined('ABSPATH')) exit;

/**
 * Disable plugin front logics in admin page
 */
class SDBOGE_DisplayContext {
	public function __construct(private readonly SDBOGE_Settings $settings) {}

	function sdboge_should_boot(): bool {
		return !$this->sdboge_is_admin() && $this->settings->sdboge_enabled() && !defined('DOING_AJAX');
	}

	private function sdboge_is_admin(): bool {
		return is_admin();
	}
}