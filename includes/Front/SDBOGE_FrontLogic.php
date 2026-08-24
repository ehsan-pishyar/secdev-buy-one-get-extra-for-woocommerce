<?php

namespace SDBOGE\Front;

use SDBOGE\Front\Messages\SDBOGE_AlternativeRewardMessage;
use SDBOGE\Front\Cart\SDBOGE_RewardCartCleanup;
use SDBOGE\Front\Cart\SDBOGE_RewardCartRestrictions;
use SDBOGE\Front\Rewards\SDBOGE_RewardSynchronizer;

if (!defined('ABSPATH')) exit;

/**
 * Register logic
 */
class SDBOGE_FrontLogic {
	public function __construct(
		private readonly SDBOGE_RewardSynchronizer $rewardSynchronizer,
		private readonly SDBOGE_RewardCartRestrictions $rewardCartRestrictions,
		private readonly SDBOGE_RewardCartCleanup $rewardCartCleanup,
		private readonly SDBOGE_AlternativeRewardMessage $alternativeRewardMessage
	) {
		$this->register_hooks();
	}

	private function register_hooks(): void {
		/**
		 * Keep reward items synced with eligible products.
		 */
		add_action( 'woocommerce_before_calculate_totals', [$this->rewardSynchronizer, 'sdboge_sync' ], 20 );

		/**
		 * Prevent removing reward items.
		 */
		add_filter( 'woocommerce_cart_item_remove_link', [$this->rewardCartRestrictions, 'sdboge_prevent_removal' ], 10, 2 );

		/**
		 * Prevent changing reward quantity.
		 */
		add_filter( 'woocommerce_cart_item_quantity', [$this->rewardCartRestrictions, 'sdboge_prevent_quantity_change' ], 10, 3 );

		/**
		 * Remove reward when its main product is removed.
		 */
		add_action( 'woocommerce_cart_item_removed', [$this->rewardCartCleanup, 'sdboge_remove_reward_with_trigger' ], 10, 2 );

		/**
		 * After cart table hook for an alternative reward product message.
		 */
		add_action( 'woocommerce_before_cart_contents', [$this->alternativeRewardMessage, 'sdboge_render' ] );
	}
}