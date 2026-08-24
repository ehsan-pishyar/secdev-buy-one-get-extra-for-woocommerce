<?php

namespace SDBOGE\Helpers;

if (!defined('ABSPATH')) exit;

class SDBOGE_RegisterSettings {
	static function sdboge_init(): void {
		add_action('admin_init', [self::class, 'sdboge_register' ]);
	}

	static function sdboge_register(): void {
		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_ENABLED,
			[
				'type' => 'boolean',
				'sanitize_callback' => [self::class, 'sdboge_sanitize_checkbox' ],
				'default' => false
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_APPLY_TO,
			[
				'sanitize_callback' => 'sanitize_text_field'
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_CATEGORIES,
			[
				'type' => 'array',
				'sanitize_callback' => [self::class, 'sdboge_sanitize_int_array' ],
				'default' => []
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_TAGS,
			[
				'type' => 'array',
				'sanitize_callback' => [self::class, 'sdboge_sanitize_int_array' ],
				'default' => []
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_PRODUCTS,
			[
				'type' => 'array',
				'sanitize_callback' => [self::class, 'sdboge_sanitize_int_array' ],
				'default' => []
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_EXCLUDE_PRODUCTS,
			[
				'type' => 'array',
				'sanitize_callback' => [self::class, 'sdboge_sanitize_int_array' ],
				'default' => []
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_EXCLUDE_COUPONS,
			[
				'type' => 'array',
				'sanitize_callback' => [self::class, 'sdboge_sanitize_int_array' ],
				'default' => []
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_MINIMUM_QUANTITY,
			[
				'sanitize_callback' => [self::class, 'sdboge_sanitize_max_int_one']
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_MINIMUM_QUANTITY_REWARD_AMOUNT,
			[
				'sanitize_callback' => 'sanitize_text_field'
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_MAXIMUM_FREE_QUANTITY_PER_ITEM,
			[
				'sanitize_callback' => [self::class, 'sdboge_sanitize_max_int_zero']
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_MAXIMUM_FREE_QUANTITY_TOTAL,
			[
				'sanitize_callback' => [self::class, 'sdboge_sanitize_max_int_zero']
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_ALTERNATIVE_REWARD_PRODUCT,
			[
				'sanitize_callback' => 'absint'
			]
		);

		register_setting(
			SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP,
			SDBOGE_SettingsConstants::SDBOGE_ALTERNATIVE_REWARD_MESSAGE,
			[
				'sanitize_callback' => 'sanitize_text_field'
			]
		);
	}

	static function sdboge_sanitize_int_array($values): array {
		return array_map('absint', (array) $values);
	}

	static function sdboge_sanitize_checkbox($value): bool {
		return !empty($value);
	}

	static function sdboge_sanitize_max_int_zero($value): int {
		return max(0, absint($value));
	}

	static function sdboge_sanitize_max_int_one($value): int {
		return max(1, abs((int) $value));
	}
}