<?php

namespace SDBOGE\Front\Rewards;

if (!defined('ABSPATH')) exit;

/**
 * Can the original product provide the reward? (Which product should be given as the reward?)
 * Yes => Use original product/variation
 * No => Find alternative reward variation
 *
 * Why this feature?
 * Well, sometimes we have only one stock quantity from our product in stock management. So when a user tries to purchase this
 * item, it cannot add another one as a reward to user Cart. So admin can determine another item in the Plugin
 * settings feature, so that alternative item adds as reward for this situation.
 */
class SDBOGE_RewardProductResolver {
	/**
	 * @param array $cart_item
	 * @param int $reward_quantity
	 * @param array $options
	 *
	 * @return array
	 * Resolve the product that should be awarded.
	 */
	function sdboge_resolve( array $cart_item, int $reward_quantity, array $options ): array {
		// The Original product has enough stocks.
		if ($this->sdboge_can_use_original_reward($cart_item, $reward_quantity)) {
			$cart_item['_sdboge_is_alternative_reward'] = false;

			return $cart_item;
		}

		// Try to find an alternative reward variation.
		$alternative_variation_id = $this->sdboge_get_alternative_variation_id( $options['alternative_reward_product'] ?? 0 );

		if (!$alternative_variation_id) return $cart_item;

		$variation = wc_get_product($alternative_variation_id);

		if (!$variation instanceof \WC_Product_Variation) return $cart_item;

		return [
			'product_id'                 => $variation->get_parent_id(),
			'variation_id'               => $variation->get_id(),
			'variation'                  => $variation->get_variation_attributes(),
			'_sdboge_is_alternative_reward' => true,
		];
	}

	/**
	 * @param array $cart_item
	 * @param int $reward_quantity
	 *
	 * @return bool
	 * Determine whether the original product can be used as the reward.
	 */
	private function sdboge_can_use_original_reward( array $cart_item, int $reward_quantity ): bool {
		$product = $cart_item['data'];

		// If admin doesn't check managing_stock option in product edit page, return that product reward.
		if (!$product->managing_stock()) return true;

		$stock = (int) ($product->get_stock_quantity() ?? 0);
		$cart_quantity = (int) $cart_item['quantity'];

		return $stock >= ($cart_quantity + $reward_quantity);
	}

	/**
	 * @param int $product_id
	 *
	 * @return int|null
	 * Finds the best available variation from the alternative reward product.
	 * Witch means get all variation stocks to return the highest variation by their stock. (Sorts them to choose the highest one)
	 *
	 * Ex:
	 * Variation 1 => 10 => Chosen one after sorting
	 * Variation 2 => 5
	 */
	private function sdboge_get_alternative_variation_id( int $product_id ): ?int {
		if (!$product_id) return null;

		$product = wc_get_product($product_id);

		if (!$product instanceof \WC_Product_Variable) return null;

		return $this->sdboge_get_instock_variation($product);
	}

	/**
	 * @param \WC_Product_Variable $parent
	 *
	 * @return int|null
	 * Finds in-stock variations.
	 */
	private function sdboge_get_instock_variation( \WC_Product_Variable $parent ): ?int {
		$variations = [];

		foreach ($parent->get_children() as $child_id) {
			$variation = wc_get_product($child_id);

			if ( !$variation instanceof \WC_Product_Variation || !$variation->is_in_stock() ) continue;

			$variations[] = $variation;
		}

		return $this->sdboge_get_most_instock_variation($variations);
	}

	/**
	 * @param array $variations
	 *
	 * @return int|null
	 * Returns the in-stock variation with the highest stock.
	 */
	private function sdboge_get_most_instock_variation( array $variations ): ?int {
		if (empty($variations)) return null;

		usort(
			$variations,
			function (
				\WC_Product_Variation $a,
				\WC_Product_Variation $b
			) {
				$a_stock = $a->get_stock_quantity() ?? 0;
				$b_stock = $b->get_stock_quantity() ?? 0;

				return $b_stock <=> $a_stock;
			}
		);

		return $variations[0]->get_id();
	}
}
