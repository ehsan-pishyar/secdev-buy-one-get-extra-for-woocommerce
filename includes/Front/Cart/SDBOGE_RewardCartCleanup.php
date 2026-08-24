<?php

namespace SDBOGE\Front\Cart;

if (!defined('ABSPATH')) exit;

/**
 * When a trigger product is removed from the cart, remove the reward item associated with it.
 */
class SDBOGE_RewardCartCleanup {
	/**
	 * @param string $cart_item_key
	 * @param $cart
	 *
	 * @return void
	 * Remove the reward when its trigger product is removed.
	 */
	function sdboge_remove_reward_with_trigger( string $cart_item_key, $cart ): void {
		$removed_item = $cart->removed_cart_contents[$cart_item_key] ?? null;

		if (empty($removed_item)) return;

		// If the removed item itself is a reward, don't remove another reward.
		if (!empty($removed_item['_sdboge_is_reward'])) return;

		$product_id = (int) ($removed_item['product_id'] ?? 0);

		$variation_id = !empty($removed_item['variation_id']) ? (int) $removed_item['variation_id'] : 0;

		foreach ($cart->get_cart() as $key => $item) {
			if (empty($item['_sdboge_is_reward'])) continue;

			if (
				(int) ($item['_sdboge_trigger_product_id'] ?? 0) === $product_id &&
				(int) ($item['_sdboge_trigger_variation_id'] ?? 0) === $variation_id
			) {
				$cart->remove_cart_item($key);
				return;
			}
		}
	}
}
