<?php

namespace SDBOGE\Front\Rewards;

use SDBOGE\Front\Cart\SDBOGE_ExcludedCouponChecker;
use SDBOGE\Front\Cart\SDBOGE_RewardCartManager;
use SDBOGE\Front\Eligibility\SDBOGE_ProductEligibility;

if (!defined('ABSPATH')) exit;

/**
 * Synchronize all reward items in the WooCommerce cart according to the current plugin settings.
 */
class SDBOGE_RewardSynchronizer {
	public function __construct(
		private readonly SDBOGE_RewardSettings $rewardSettings,
		private readonly SDBOGE_ProductEligibility $productEligibility,
		private readonly SDBOGE_RewardCalculator $rewardCalculator,
		private readonly SDBOGE_RewardProductResolver $rewardProductResolver,
		private readonly SDBOGE_RewardCartManager $rewardCartManager,
		private readonly SDBOGE_ExcludedCouponChecker $excludedCouponChecker
	) {}

	/**
	 * @param $cart
	 *
	 * @return void
	 * Synchronize reward items with eligible cart products.
	 */
	function sdboge_sync($cart): void {
		$options = $this->rewardSettings->sdboge_all();

		// Remove rewards if an excluded coupon is applied.
		if ($this->excludedCouponChecker->sdboge_has_excluded_coupon($options['excluded_coupons'])) {
			$this->rewardCartManager->sdboge_remove_all($cart);
			return;
		}

		$total_reward_quantity = 0;

		foreach ($cart->get_cart() as $cart_item) {
			// Never process reward items as trigger products.
			if (!empty($cart_item['_sdboge_is_reward'])) continue;

			$product_id = (int) $cart_item['product_id'];

			// Remove reward if the trigger product is excluded.
			if ( in_array( $product_id, $options['excluded_products'], true ) ) {
				$this->rewardCartManager->sdboge_remove( $cart, $product_id, $cart_item );
				continue;
			}

			// Check whether the product is eligible.
			if ( !$this->productEligibility->sdboge_is_product_eligible( $product_id, $options )) continue;

			$quantity = (int) $cart_item['quantity'];

			// Remove reward when the minimum quantity is not reached.
			if ($quantity < $options['minimum_quantity']) {
				$this->rewardCartManager->sdboge_remove( $cart, $product_id, $cart_item );
				continue;
			}

			// Calculate reward quantity.
			$reward_quantity = $this->rewardCalculator->sdboge_calculate(
				$quantity,
				$options['minimum_quantity_reward_amount'],
				$total_reward_quantity,
				$options['maximum_free_quantity_per_item'],
				$options['maximum_free_quantity_total']
			);

			// Keep track of total assigned rewards.
			$total_reward_quantity += $reward_quantity;

			// Resolve the actual reward product.
			$reward = $this->rewardProductResolver->sdboge_resolve(
				$cart_item,
				$reward_quantity,
				$options
			);

			// Synchronize the reward item.
			$this->rewardCartManager->sdboge_sync(
				$cart,
				$reward['product_id'],
				$reward_quantity,
				$reward,
				$cart_item
			);
		}

		$this->rewardCartManager->sdboge_make_rewards_free($cart);
	}
}
