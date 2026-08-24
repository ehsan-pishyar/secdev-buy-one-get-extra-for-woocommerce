<?php

namespace SDBOGE\Front\Rewards;

if (!defined('ABSPATH')) exit;

/**
 * Calculates reward amount for trigger item based on plugin limitation per item or total
 */
class SDBOGE_RewardCalculator {
	/**
	 * @param int $cart_quantity
	 * @param string $reward_count
	 * @param int $total_reward_quantity
	 * @param int $maximum_free_quantity_per_item => Gets maximum_free_quantity_per_item from database
	 * @param int $maximum_free_quantity_total => Gets maximum_free_quantity_total from the database
	 *
	 * @return int
	 * Calculate the reward quantity for a cart item.
	 */
	function sdboge_calculate(
		int $cart_quantity,
		string $reward_count,
		int $total_reward_quantity,
		int $maximum_free_quantity_per_item,
		int $maximum_free_quantity_total
	): int {
		$reward_quantity = $this->sdboge_get_reward_quantity( $cart_quantity, $reward_count );
		$reward_quantity = $this->sdboge_apply_per_item_limit( $reward_quantity, $maximum_free_quantity_per_item );

		return $this->sdboge_apply_total_limit(
			$reward_quantity,
			$total_reward_quantity,
			$maximum_free_quantity_total
		);
	}

	/**
	 * @param int $cart_quantity
	 * @param string $reward_count => (Feature options => same_as_cart_amount, 1)
	 *
	 * @return int
	 * Determine the reward quantity.
	 */
	private function sdboge_get_reward_quantity( int $cart_quantity, string $reward_count ): int {
		return match ($reward_count) {
			'same_as_cart_amount' => $cart_quantity,
			default => 1,
		};
	}

	/**
	 * @param int $reward_quantity
	 * @param int $maximum => (Feature options => maximum reward quantity per item)
	 *
	 * @return int
	 * Apply the maximum free quantity per item.
	 * 0 = unlimited.
	 */
	private function sdboge_apply_per_item_limit( int $reward_quantity, int $maximum ): int {
		if ($maximum <= 0) return $reward_quantity;

		return min($reward_quantity, $maximum);
	}

	/**
	 * @param int $reward_quantity
	 * @param int $total_reward_quantity
	 * @param int $maximum => (Feature options => maximum reward quantity total)
	 *
	 * @return int
	 * Apply the maximum total free reward quantity.
	 * 0 = unlimited.
	 */
	private function sdboge_apply_total_limit( int $reward_quantity, int $total_reward_quantity, int $maximum ): int {
		if ($maximum <= 0) return $reward_quantity;

		$remaining_capacity = max( 0, $maximum - $total_reward_quantity );

		return min( $reward_quantity, $remaining_capacity );
	}

}