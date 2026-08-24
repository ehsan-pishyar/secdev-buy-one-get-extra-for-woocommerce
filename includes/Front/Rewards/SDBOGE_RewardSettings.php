<?php

namespace SDBOGE\Front\Rewards;

use SDBOGE\Admin\SDBOGE_Settings;

if (!defined('ABSPATH')) exit;

class SDBOGE_RewardSettings {
	public function __construct(private readonly SDBOGE_Settings $settings) {}

	/**
	 * @return array
	 * Get the plugin options required for reward synchronization.
	 */
	function sdboge_all(): array {
		return [
			'apply_to' => $this->settings->sdboge_apply_to(),
			'categories' => array_map( 'intval', $this->settings->sdboge_apply_to_categories()),
			'tags' => array_map( 'intval', $this->settings->sdboge_apply_to_tags()),
			'products' => array_map( 'intval', $this->settings->sdboge_apply_to_products()),
			'minimum_quantity' => $this->settings->sdboge_minimum_quantity(),
			'minimum_quantity_reward_amount' => $this->settings->sdboge_minimum_quantity_reward_amount(),
			'maximum_free_quantity_per_item' => $this->settings->sdboge_maximum_free_quantity_per_item(),
			'maximum_free_quantity_total' => $this->settings->sdboge_maximum_free_quantity_total(),
			'alternative_reward_product' => $this->settings->sdboge_alternative_reward_product(),
			'excluded_products' => array_map( 'intval', $this->settings->sdboge_exclude_products()),
			'excluded_coupons' => array_map( 'intval', $this->settings->sdboge_exclude_coupons())
		];
	}
}
