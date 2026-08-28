<?php

namespace SDBOGE\Front;

use SDBOGE\Admin\SDBOGE_Settings;
use SDBOGE\Front\Cart\SDBOGE_ExcludedCouponChecker;
use SDBOGE\Front\Cart\SDBOGE_RewardCartCleanup;
use SDBOGE\Front\Cart\SDBOGE_RewardCartManager;
use SDBOGE\Front\Cart\SDBOGE_RewardCartRestrictions;
use SDBOGE\Front\Eligibility\SDBOGE_ProductEligibility;
use SDBOGE\Front\Messages\SDBOGE_AlternativeRewardMessage;
use SDBOGE\Front\Rewards\SDBOGE_RewardCalculator;
use SDBOGE\Front\Rewards\SDBOGE_RewardProductResolver;
use SDBOGE\Front\Rewards\SDBOGE_RewardSettings;
use SDBOGE\Front\Rewards\SDBOGE_RewardSynchronizer;
use SDBOGE\Helpers\SDBOGE_DisplayContext;

if (!defined('ABSPATH')) exit;

class SDBOGE_Bootstrap {
	private static ?self $instance = null;

	static function instance(): ?self {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
        $this->sdboge_boot_front();
	}

    function sdboge_boot_front(): void {
        $settings = new SDBOGE_Settings();
        $displayContext = new SDBOGE_DisplayContext($settings);

        if (!$displayContext->sdboge_should_boot()) return;

        $rewardSettings = new SDBOGE_RewardSettings($settings);
        $productEligibility = new SDBOGE_ProductEligibility();
        $rewardCalculator = new SDBOGE_RewardCalculator();
        $rewardProductResolver = new SDBOGE_RewardProductResolver();
        $rewardCartManager = new SDBOGE_RewardCartManager();
        $rewardCartRestrictions = new SDBOGE_RewardCartRestrictions();
        $rewardCartCleanup = new SDBOGE_RewardCartCleanup();
        $alternativeRewardMessage = new SDBOGE_AlternativeRewardMessage($settings);
        $excludedCouponChecker = new SDBOGE_ExcludedCouponChecker();

        $rewardSynchronizer = new SDBOGE_RewardSynchronizer(
            $rewardSettings,
            $productEligibility,
            $rewardCalculator,
            $rewardProductResolver,
            $rewardCartManager,
            $excludedCouponChecker
        );

        new SDBOGE_FrontLogic(
            $rewardSynchronizer,
            $rewardCartRestrictions,
            $rewardCartCleanup,
            $alternativeRewardMessage,
        );
    }
}
