<?php

namespace SDBOGE\Helpers;

if (!defined('ABSPATH')) exit;

class SDBOGE_SettingsHelpers {
	static function sdboge_get_product_categories(): ?array {
		$categories = get_terms([
			'taxonomy'   => 'product_cat',
			'hide_empty' => false
		]);

		if (is_wp_error($categories) || empty($categories)) return null;

		return $categories;
	}

	public static function sdboge_render_categories (
		array $categories,
		array $selected,
		int $parent = 0,
		int $depth = 0
	): void {
		foreach ($categories as $category) {
			if ((int) $category->parent !== $parent) continue;

			printf(
				'<option value="%1$d" %2$s>%3$s%4$s</option>',
				esc_attr($category->term_id),
				selected(in_array($category->term_id, $selected, true), true, false),
				esc_html(str_repeat('— ', $depth)),
				esc_html($category->name)
			);

			self::sdboge_render_categories(
				$categories,
				$selected,
				(int) $category->term_id,
				$depth + 1
			);
		}
	}

	static function sdboge_get_product_tags(): ?array {
		$tags = get_terms([
			'taxonomy'   => 'product_tag',
			'hide_empty' => false,
		]);

		if (is_wp_error($tags) || empty($tags)) return null;

		return $tags;
	}

	static function sdboge_get_all_products(): ?array {
		return wc_get_products([
			'status'  => 'publish',
			'limit'   => -1,
			'orderby' => 'title',
			'order'   => 'DESC',
		]);
	}

	static function sdboge_get_all_coupons(): ?array {
		$coupons = get_posts([
			'post_type'      => 'shop_coupon',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		]);

		if (empty($coupons)) return [];

		return array_map(
			static fn($coupon) => new \WC_Coupon($coupon->ID),
			$coupons
		);
	}

	static function sdboge_apply_to_options(): array {
		return [
			'all'       => __('All', 'secdev-buy-one-get-extra-for-woocommerce'),
			'category'  => __('Category', 'secdev-buy-one-get-extra-for-woocommerce'),
			'tag'       => __('Tag', 'secdev-buy-one-get-extra-for-woocommerce'),
			'product'   => __('Product', 'secdev-buy-one-get-extra-for-woocommerce'),
		];
	}

	static function sdboge_minimum_quantity_reward_amount(): array {
		return [
			'one' => __('1', 'secdev-buy-one-get-extra-for-woocommerce'),
			'same_as_cart_amount' => __('Same as Cart Item Amount', 'secdev-buy-one-get-extra-for-woocommerce'),
		];
	}
}
