<?php

if (!defined('ABSPATH')) exit;

if (
	! is_admin() ||
	! (
		current_user_can('administrator') ||
		current_user_can('manage_options')
	)
) {
	exit;
}

use SDBOGE\Helpers\SDBOGE_SettingsConstants;
use SDBOGE\Helpers\SDBOGE_SettingsHelpers;
use SDBOGE\Admin\SDBOGE_Settings;

$sdboge_apply_to_options                = SDBOGE_SettingsHelpers::sdboge_apply_to_options();
$sdboge_categories                      = SDBOGE_SettingsHelpers::sdboge_get_product_categories();
$sdboge_tags                            = SDBOGE_SettingsHelpers::sdboge_get_product_tags();
$sdboge_products                        = SDBOGE_SettingsHelpers::sdboge_get_all_products();
$sdboge_coupons                         = SDBOGE_SettingsHelpers::sdboge_get_all_coupons();
$sdboge_minimum_quantity                = SDBOGE_Settings::sdboge_minimum_quantity();
$sdboge_minimum_quantity_reward_amount  = SDBOGE_SettingsHelpers::sdboge_minimum_quantity_reward_amount();
$sdboge_maximum_free_quantity_per_item  = SDBOGE_Settings::sdboge_maximum_free_quantity_per_item();
$sdboge_maximum_free_quantity_total     = SDBOGE_Settings::sdboge_maximum_free_quantity_total();
$sdboge_alternative_reward_message      = SDBOGE_Settings::sdboge_alternative_reward_message();

settings_errors();
?>

<div class="flex flex-col m-8">
    <form action="options.php" method="post">
        <?php settings_fields( SDBOGE_SettingsConstants::SDBOGE_SETTINGS_GROUP ); ?>
        <div class="flex xl:flex-col w-5xl p-[2rem] shadow-[0_10px_40px_rgba(41,50,65,.20)] rounded-[24px] bg-white">
            <img
                class="w-32 h-auto"
                src="<?php echo esc_url(SDBOGE_URI . 'assets/imgs/secdev-logo.webp'); ?>"
                alt="<?php esc_attr_e('SecDev Logo', 'secdev-buy-one-get-extra-for-woocommerce'); ?>"
            >
            <div class="flex xl:flex-row my-5 gap-3">
                <div class="flex flex-col flex-1/2 justify-center items-start">
                    <span class="text-[16px] font-semibold text-[#0b2545]"><?php echo esc_html__('SecDev Buy One Get Extra for WooCommerce', 'secdev-buy-one-get-extra-for-woocommerce');?></span>
                </div>
                <!-- Save button -->
                <div class="flex flex-col flex-1/2 justify-center items-end">
                    <button
                        type="submit"
                        class="w-30 bg-[#ef476f] px-3 py-2 rounded-lg text-white hover:cursor-pointer"
                    >
		                <?php echo esc_html__('Save Settings', 'secdev-buy-one-get-extra-for-woocommerce') ?>
                    </button>
                </div>
            </div>

            <hr>

            <!-- Start the Engine -->
            <div class="flex xl:flex-row my-5 gap-3">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_ENABLED) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Enable', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Enable or disable the plugin.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-1/2 items-start">
                    <input
                        type="checkbox"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_ENABLED) ?>"
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_ENABLED) ?>"
                        value="1"
                    <?php checked(SDBOGE_Settings::sdboge_enabled()); ?>
                    >
                </div>
            </div>

            <hr>

            <!-- Apply to: (All products, Category, Tag, Manual (Single product)) -->
            <div class="flex xl:flex-row my-5 gap-3" id="sdboge-apply-to-section">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Apply to', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Choose which products can trigger a free reward: all products, selected categories, selected tags, or specific products.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-col flex-1/2 items-start">
	                <?php $sdboge_current = SDBOGE_Settings::sdboge_apply_to(); ?>
                    <select
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO) ?>"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO) ?>"
                        class="rounded-xl select2"
                    >
		                <?php foreach($sdboge_apply_to_options as $sdboge_key => $sdboge_value) : ?>
                            <option value="<?php echo esc_attr($sdboge_key); ?>" <?php selected($sdboge_current, $sdboge_key); ?>>
				                <?php echo esc_html($sdboge_value); ?>
                            </option>
		                <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <hr id="sdboge-apply-to-hr">

            <!-- Apply to Categories -->
            <div class="flex xl:flex-row my-5 gap-3" id="sdboge-apply-to-categories-section">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_CATEGORIES) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Apply to Categories', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Select the product categories that can trigger a free reward.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-col flex-1/2 items-start">
                    <select
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_CATEGORIES) ?>[]"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_CATEGORIES) ?>"
                        class="rounded-xl select2"
                        multiple
                    >
		                <?php $sdboge_selected = SDBOGE_Settings::sdboge_apply_to_categories(); ?>
		                <?php if($sdboge_categories):
			                SDBOGE_SettingsHelpers::sdboge_render_categories($sdboge_categories, $sdboge_selected);
		                endif;?>
                    </select>
                </div>
            </div>

            <!-- Apply to Tags -->
            <div class="flex xl:flex-row my-5 gap-3" id="sdboge-apply-to-tags-section">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_TAGS) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Apply to Tags', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Select the product tags that can trigger a free reward.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-col flex-1/2 items-start">
                    <select
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_TAGS) ?>[]"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_TAGS) ?>"
                        class="rounded-xl select2"
                        multiple
                    >
		                <?php $sdboge_selected = SDBOGE_Settings::sdboge_apply_to_tags(); ?>
		                <?php if($sdboge_tags) : ?>
			                <?php foreach($sdboge_tags as $sdboge_tag) : ?>
                                <option value="<?php echo esc_attr($sdboge_tag->term_id); ?>" <?php selected(in_array($sdboge_tag->term_id, $sdboge_selected, true));?>>
					                <?php echo esc_html($sdboge_tag->name); ?>
                                </option>
			                <?php endforeach; ?>
		                <?php endif;?>
                    </select>
                </div>
            </div>

            <!-- Apply to Products -->
            <div class="flex xl:flex-row my-5 gap-3" id="sdboge-apply-to-products-section">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_PRODUCTS) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Apply to Products', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Select the specific products that can trigger a free reward.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-col flex-1/2 items-start">
                    <select
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_PRODUCTS) ?>[]"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_APPLY_TO_PRODUCTS) ?>"
                        class="rounded-xl select2"
                        multiple
                    >
		                <?php $sdboge_selected = SDBOGE_Settings::sdboge_apply_to_products(); ?>
		                <?php if($sdboge_products) : ?>
			                <?php foreach($sdboge_products as $sdboge_product) : ?>
                                <option value="<?php echo esc_attr($sdboge_product->get_id()); ?>" <?php selected(in_array($sdboge_product->get_id(), $sdboge_selected, true));?>>
					                <?php echo esc_html($sdboge_product->get_name()); ?>
                                </option>
			                <?php endforeach; ?>
		                <?php endif;?>
                    </select>
                </div>
            </div>

            <hr>

            <!-- Exclude Products -->
            <div class="flex xl:flex-row my-5 gap-3">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_EXCLUDE_PRODUCTS) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Exclude Products', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Select products that should never trigger a free reward, even if they match the promotion rules.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-col flex-1/2 items-start">
                    <select
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_EXCLUDE_PRODUCTS) ?>[]"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_EXCLUDE_PRODUCTS) ?>"
                        class="rounded-xl select2"
                        multiple
                    >
				        <?php $sdboge_selected = SDBOGE_Settings::sdboge_exclude_products(); ?>
				        <?php if($sdboge_products) : ?>
					        <?php foreach($sdboge_products as $sdboge_product) : ?>
                                <option value="<?php echo esc_attr($sdboge_product->get_id()); ?>" <?php selected(in_array($sdboge_product->get_id(), $sdboge_selected, true));?>>
							        <?php echo esc_html($sdboge_product->get_name()); ?>
                                </option>
					        <?php endforeach; ?>
				        <?php endif;?>
                    </select>
                </div>
            </div>

            <hr>

            <!-- Exclude Coupons -->
            <div class="flex xl:flex-row my-5 gap-3">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_EXCLUDE_COUPONS) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Exclude Coupons', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Select coupons that disable the promotion when applied to the cart.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-col flex-1/2 items-start">
                    <select
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_EXCLUDE_COUPONS) ?>[]"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_EXCLUDE_COUPONS) ?>"
                        class="rounded-xl select2"
                        multiple
                    >
				        <?php $sdboge_selected = SDBOGE_Settings::sdboge_exclude_coupons(); ?>
				        <?php if($sdboge_coupons) : ?>
					        <?php foreach($sdboge_coupons as $sdboge_coupon) : ?>
                                <option value="<?php echo esc_attr($sdboge_coupon->get_id()); ?>" <?php selected(in_array($sdboge_coupon->get_id(), $sdboge_selected, true));?>>
							        <?php echo esc_html($sdboge_coupon->get_code()); ?>
                                </option>
					        <?php endforeach; ?>
				        <?php endif;?>
                    </select>
                </div>
            </div>

            <hr>

            <!-- Minimum Quantity -->
            <div class="flex xl:flex-row my-5 gap-3">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MINIMUM_QUANTITY) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Minimum Purchase Quantity', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Set the minimum quantity of a product required to trigger a free reward.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-col flex-1/2 items-start">
                    <input
                        type="number"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MINIMUM_QUANTITY) ?>"
                        class="w-full"
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MINIMUM_QUANTITY) ?>"
                        value="<?php echo esc_attr($sdboge_minimum_quantity) ?>"
                        min="1"
                        step="1"
                    >
                </div>
            </div>

            <hr>

            <!-- Minimum Quantity Reward Amount -->
            <div class="flex xl:flex-row my-5 gap-3">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MINIMUM_QUANTITY_REWARD_AMOUNT) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Reward Quantity', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Choose how many free items the customer receives when the minimum purchase quantity is reached.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Ex. Minimum Purchase Quantity: 2, Reward Quantity: 1', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Meaning: Buy 2 => Get 1 free', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-col flex-1/2 items-start">
			        <?php $sdboge_current = SDBOGE_Settings::sdboge_minimum_quantity_reward_amount(); ?>
                    <select
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MINIMUM_QUANTITY_REWARD_AMOUNT) ?>"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MINIMUM_QUANTITY_REWARD_AMOUNT) ?>"
                        class="rounded-xl select2"
                    >
				        <?php foreach($sdboge_minimum_quantity_reward_amount as $sdboge_key => $sdboge_value) : ?>
                            <option value="<?php echo esc_attr($sdboge_key); ?>" <?php selected($sdboge_current, $sdboge_key); ?>>
						        <?php echo esc_html($sdboge_value); ?>
                            </option>
				        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <hr>

            <!-- Maximum Free Quantity Per Item -->
            <div class="flex xl:flex-row my-5 gap-3">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MAXIMUM_FREE_QUANTITY_PER_ITEM) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Maximum Reward Quantity per Product', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Set the maximum number of free items that can be awarded for each eligible product.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Enter 0 for no limit.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>

                </div>
                <div class="flex flex-col flex-1/2 items-start">
                    <input
                        type="number"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MAXIMUM_FREE_QUANTITY_PER_ITEM) ?>"
                        class="w-full"
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MAXIMUM_FREE_QUANTITY_PER_ITEM) ?>"
                        value="<?php echo esc_attr($sdboge_maximum_free_quantity_per_item) ?>"
                    >
                </div>
            </div>

            <hr>

            <!-- Maximum Free Quantity Total -->
            <div class="flex xl:flex-row my-5 gap-3">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MAXIMUM_FREE_QUANTITY_TOTAL) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Maximum Total Reward Quantity', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Set the maximum total number of free items that can be awarded in a single cart.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Enter 0 for no limit.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-col flex-1/2 items-start">
                    <input
                        type="number"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MAXIMUM_FREE_QUANTITY_TOTAL) ?>"
                        class="w-full"
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_MAXIMUM_FREE_QUANTITY_TOTAL) ?>"
                        value="<?php echo esc_attr($sdboge_maximum_free_quantity_total) ?>"
                    >
                </div>
            </div>

            <hr>

            <!-- Alternative Reward Product -->
            <div class="flex xl:flex-row my-5 gap-3">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_ALTERNATIVE_REWARD_PRODUCT) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Alternative Reward Product', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Choose the product to give as a replacement reward when the original product is unavailable or cannot be added as a reward.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Why this feature? Sometimes user adds an item to the Cart that just 1 stock of that item remains. So it cannot add as reward. You Specify an alternative item for this situation.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-col flex-1/2 items-start">
			        <?php $sdboge_current = SDBOGE_Settings::sdboge_alternative_reward_product(); ?>
                    <select
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_ALTERNATIVE_REWARD_PRODUCT) ?>"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_ALTERNATIVE_REWARD_PRODUCT) ?>"
                        class="rounded-xl select2"
                    >
				        <?php foreach($sdboge_products as $sdboge_product) : ?>
                            <option value="<?php echo esc_attr($sdboge_product->get_id()); ?>" <?php selected($sdboge_current, $sdboge_product->get_id()); ?>>
						        <?php echo esc_html($sdboge_product->get_name()); ?>
                            </option>
				        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <hr>

            <!-- Alternative Reward Message -->
            <div class="flex xl:flex-row my-5 gap-3">
                <div class="flex flex-col flex-1/2">
                    <label for="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_ALTERNATIVE_REWARD_MESSAGE) ?>" class="font-bold text-[#0b2545]"><?php echo esc_html__('Alternative Reward Message', 'secdev-buy-one-get-extra-for-woocommerce') ?></label>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Enter a message to explain why an alternative reward was added to the Cart.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                    <span class="text-[13px] text-[#999999]"><?php echo esc_html__('Ex. From your purchased item has just 1 remains. So we add alternative reward for you.', 'secdev-buy-one-get-extra-for-woocommerce') ?></span>
                </div>
                <div class="flex flex-col flex-1/2 items-start">
                    <input
                        type="text"
                        id="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_ALTERNATIVE_REWARD_MESSAGE) ?>"
                        class="w-full"
                        name="<?php echo esc_attr(SDBOGE_SettingsConstants::SDBOGE_ALTERNATIVE_REWARD_MESSAGE) ?>"
                        placeholder="<?php echo esc_attr__('Explain here', 'secdev-buy-one-get-extra-for-woocommerce') ?>"
                        value="<?php echo esc_attr($sdboge_alternative_reward_message) ?>"
                    >
                </div>
            </div>

        </div>
    </form>

</div>