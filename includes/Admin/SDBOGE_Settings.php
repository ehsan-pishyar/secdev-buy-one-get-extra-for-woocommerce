<?php

namespace SDBOGE\Admin;

use SDBOGE\Helpers\SDBOGE_SettingsConstants;

if (!defined('ABSPATH')) exit;

class SDBOGE_Settings {
	static function sdboge_enabled(): bool {
		return get_option(SDBOGE_SettingsConstants::SDBOGE_ENABLED, '0') === '1';
	}

	static function sdboge_apply_to(): string {
		return get_option(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO, 'all');
	}

	static function sdboge_apply_to_categories(): array {
		return array_map('absint', get_option(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_CATEGORIES, []));
	}

	static function sdboge_apply_to_tags(): array {
		return array_map('absint', get_option(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_TAGS, []));
	}

	static function sdboge_apply_to_products(): array {
		return array_map('absint', get_option(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_PRODUCTS, []));
	}

	static function sdboge_exclude_products(): array {
		return array_map('absint', get_option(SDBOGE_SettingsConstants::SDBOGE_EXCLUDE_PRODUCTS, []));
	}

	static function sdboge_exclude_coupons(): array {
		return array_map('absint', get_option(SDBOGE_SettingsConstants::SDBOGE_EXCLUDE_COUPONS, []));
	}

	static function sdboge_minimum_quantity(): int {
		return max(1, abs((int) get_option(SDBOGE_SettingsConstants::SDBOGE_MINIMUM_QUANTITY, 1)));
	}

	static function sdboge_minimum_quantity_reward_amount(): string {
		return get_option(SDBOGE_SettingsConstants::SDBOGE_MINIMUM_QUANTITY_REWARD_AMOUNT, '');
	}

	static function sdboge_maximum_free_quantity_per_item(): int {
		return abs((int) get_option(SDBOGE_SettingsConstants::SDBOGE_MAXIMUM_FREE_QUANTITY_PER_ITEM, 0));
	}

	static function sdboge_maximum_free_quantity_total(): int {
		return abs((int) get_option(SDBOGE_SettingsConstants::SDBOGE_MAXIMUM_FREE_QUANTITY_TOTAL, 0));
	}

	static function sdboge_alternative_reward_product(): int {
		return absint(get_option(SDBOGE_SettingsConstants::SDBOGE_ALTERNATIVE_REWARD_PRODUCT, 1));
	}

	static function sdboge_alternative_reward_message(): string {
		return get_option(SDBOGE_SettingsConstants::SDBOGE_ALTERNATIVE_REWARD_MESSAGE, '');
	}
}