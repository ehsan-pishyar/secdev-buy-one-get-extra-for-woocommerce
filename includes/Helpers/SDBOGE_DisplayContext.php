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
        if (!$this->settings::sdboge_enabled()) return false;
        if (is_admin() && !wp_doing_ajax()) return false;

        return true;
    }
}