<?php

namespace SDBOGE\Front\Cart;

if (!defined('ABSPATH')) exit;

/**
 * Determine whether the cart currently contains a coupon excluded by the plugin configuration.
 */
class SDBOGE_ExcludedCouponChecker {
	/**
	 * @param array $excluded_coupon_ids
	 *
	 * @return bool
	 * Determine whether an excluded coupon is applied.
	 */
	function sdboge_has_excluded_coupon( array $excluded_coupon_ids ): bool {
		if (empty($excluded_coupon_ids)) return false;

		$cart = WC()->cart;

		if (!$cart) return false;

		$applied_coupon_codes = $cart->get_applied_coupons();

		if (empty($applied_coupon_codes)) return false;

		foreach ($applied_coupon_codes as $coupon_code) {
			$coupon = new \WC_Coupon($coupon_code);

			$coupon_id = $coupon->get_id();

			if ( $coupon_id > 0 && in_array( $coupon_id, $excluded_coupon_ids, true )) return true;
		}

		return false;
	}
}
