<?php

namespace SDBOGE\Front\Messages;

use SDBOGE\Admin\SDBOGE_Settings;

if (!defined('ABSPATH')) exit;

class SDBOGE_AlternativeRewardMessage {

	public function __construct(private readonly SDBOGE_Settings $settings) {}

	/**
	 * @return void
	 * Display the alternative reward message when an alternative reward exists in the cart.
	 */
	function sdboge_render(): void {
		$message = $this->settings->sdboge_alternative_reward_message();

		if (empty($message)) return;

		if (!$this->sdboge_has_alternative_reward()) return;

		$this->sdboge_load_template($message);
	}

	/**
	 * @return bool
	 * Check whether the cart contains an alternative reward item.
	 */
	private function sdboge_has_alternative_reward(): bool {
		if (!WC()->cart) return false;

		foreach (WC()->cart->get_cart() as $cart_item) {
			if (!empty($cart_item['_sdboge_is_alternative_reward'])) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return void
	 * Load the alternative reward message template.
	 */
	private function sdboge_load_template(string $message): void {
		$template = trailingslashit(SDBOGE_PATH) . 'includes/Front/Templates/SDBOGE_MessageTemplate.php';

		if (is_readable($template)) include $template;
	}

}
