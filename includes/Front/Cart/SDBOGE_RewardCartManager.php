<?php

namespace SDBOGE\Front\Cart;

if (!defined('ABSPATH')) exit;

/**
 * This class responsible for finding, adding, updating, removing, and pricing reward items.
 */
class SDBOGE_RewardCartManager {
	/**
	 * @param $cart
	 * @param int $product_id
	 * @param int $quantity
	 * @param array $reward_item
	 * @param array $trigger_item
	 *
	 * @return void
	 * Synchronize a reward item with its trigger product.
	 */
	public function sdboge_sync(
		$cart,
		int $product_id,
		int $quantity,
		array $reward_item,
		array $trigger_item
	): void {
		$reward_variation_id = !empty($reward_item['variation_id']) ? (int) $reward_item['variation_id'] : 0;
		$trigger_product_id = (int) $trigger_item['product_id'];
		$trigger_variation_id = !empty($trigger_item['variation_id']) ? (int) $trigger_item['variation_id'] : 0;
		$reward_key = $this->sdboge_find_reward_key( $cart, $trigger_product_id, $trigger_variation_id );

		/*
		 * No reward should exist.
		 */
		if ($quantity <= 0) {
			if ($reward_key !== false) {
				$cart->remove_cart_item($reward_key);
			}

			return;
		}

		/*
		 * Reward already exists.
		 */
		if ($reward_key !== false) {
			$current_quantity = (int) ( $cart->cart_contents[$reward_key]['quantity'] );

			if ($current_quantity !== $quantity) {
				$cart->cart_contents[$reward_key]['quantity'] = $quantity;
			}

			return;
		}

		/*
		 * Add new reward.
		 */
		$variation = !empty($reward_item['variation']) ? $reward_item['variation'] : [];

		$cart->add_to_cart(
			$product_id,
			$quantity,
			$reward_variation_id,
			$variation,
			[
				'_sdboge_is_reward' => true,

				'_sdboge_is_alternative_reward' =>
					!empty($reward_item['_sdboge_is_alternative_reward']),

				'_sdboge_trigger_product_id' =>
					$trigger_product_id,

				'_sdboge_trigger_variation_id' =>
					$trigger_variation_id,
			]
		);
	}

	/**
	 * @param $cart
	 * @param int $product_id
	 * @param array $cart_item
	 *
	 * @return void
	 * Remove the reward belonging to a trigger product.
	 */
	public function sdboge_remove( $cart, int $product_id, array $cart_item ): void {
		$variation_id = !empty($cart_item['variation_id']) ? (int) $cart_item['variation_id'] : 0;
		$reward_key = $this->sdboge_find_reward_key( $cart, $product_id, $variation_id );

		if ($reward_key !== false) {
			$cart->remove_cart_item($reward_key);
		}
	}

	/**
	 * @param $cart
	 *
	 * @return void
	 * Remove all reward items from the cart.
	 */
	public function sdboge_remove_all($cart): void {
		foreach ($cart->get_cart() as $key => $cart_item) {
			if (!empty($cart_item['_sdboge_is_reward'])) {
				$cart->remove_cart_item($key);
			}
		}
	}

	/**
	 * @param $cart
	 *
	 * @return void
	 * Ensure every reward product has a zero price.
	 */
	public function sdboge_make_rewards_free($cart): void {
		foreach ($cart->get_cart() as $cart_item) {
			if ( empty($cart_item['_sdboge_is_reward']) || empty($cart_item['data']) || !is_object($cart_item['data']) ) continue;

			$cart_item['data']->set_price(0);
		}
	}

	/**
	 * @param $cart
	 * @param int $trigger_product_id
	 * @param int $trigger_variation_id
	 *
	 * @return string|false
	 * Find the reward cart item belonging to a trigger.
	 */
	private function sdboge_find_reward_key( $cart, int $trigger_product_id, int $trigger_variation_id ): string|false {
		foreach ($cart->get_cart() as $key => $item) {
			if (empty($item['_sdboge_is_reward'])) continue;

			if ( (int) ($item['_sdboge_trigger_product_id'] ?? 0) === $trigger_product_id &&
				(int) ($item['_sdboge_trigger_variation_id'] ?? 0) === $trigger_variation_id
			) {
				return $key;
			}
		}

		return false;
	}
}
