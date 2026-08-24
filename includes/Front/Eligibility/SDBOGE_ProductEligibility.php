<?php

namespace SDBOGE\Front\Eligibility;

use SDBOGE\Helpers\SDBOGE_FrontHelpers;

if (!defined('ABSPATH')) exit;

/**
 * Given a product and the configured targeting rules, determine whether the product is eligible.
 */
class SDBOGE_ProductEligibility {
	/**
	 * Determine whether a product should receive a reward.
	 */
	function sdboge_is_product_eligible( int $product_id, array $options ): bool {
		return match ( $options['apply_to'] ) {
			'all' => true,
			'category' => SDBOGE_FrontHelpers::sdboge_product_in_category_ids( $product_id, $options['categories'] ),
			'tag' => SDBOGE_FrontHelpers::sdboge_product_in_tag_ids( $product_id, $options['tags'] ),
			'product' => in_array( $product_id, $options['products'], true ),
			default => false,
		};

	}
}
