<?php

namespace SDBOGE\Front\Cart;

if (!defined('ABSPATH')) exit;

/**
 * $this class protects reward items from being manually modified by the customer.
 */
class SDBOGE_RewardCartRestrictions {
	/**
	 * @param string $html
	 * @param string $cart_item_key
	 *
	 * @return string
	 * Prevent customers from removing reward items.
	 */
	function sdboge_prevent_removal( string $html, string $cart_item_key ): string {
		$item = WC()->cart->get_cart_item($cart_item_key);

		return !empty($item['_sdboge_is_reward']) ? '' : $html;
	}

	/**
	 * @param string $html
	 * @param string $cart_item_key
	 * @param array $cart_item
	 *
	 * @return string
	 * Prevent customers from changing reward quantities.
	 */
	function sdboge_prevent_quantity_change( string $html, string $cart_item_key, array $cart_item ): string {
		return !empty($cart_item['_sdboge_is_reward']) ? (string) $cart_item['quantity'] : $html;
	}
}
