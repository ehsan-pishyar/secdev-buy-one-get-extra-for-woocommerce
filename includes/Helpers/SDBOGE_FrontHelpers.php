<?php

namespace SDBOGE\Helpers;

if (!defined('ABSPATH')) exit;

class SDBOGE_FrontHelpers {
	static function sdboge_product_in_category_ids( $product_id, $category_ids ): bool {
		// normalize category ids to integers
		$category_ids = array_map( 'intval', (array) $category_ids );

		if ( empty( $category_ids ) ) return false;

		// if it's a variation, check the parent product categories
		if ( function_exists('wc_get_product') ) {
			$product = wc_get_product( $product_id );
			if ( $product && $product->is_type( 'variation' ) ) {
				$product_id = $product->get_parent_id();
			}
		}

		// get product category term IDs (ints)
		if ( function_exists('wc_get_product_term_ids') ) {
			$term_ids = wc_get_product_term_ids( $product_id, 'product_cat' ); // returns ints
		} else {
			// fallback if Woo helper isn't available
			$terms = get_the_terms( $product_id, 'product_cat' );
			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return false;
			}
			$term_ids = array_map( fn($t) => (int) $t->term_id, $terms );
		}

		// intersect int arrays
		return !empty( array_intersect( $category_ids, $term_ids ) );
	}

	static function sdboge_product_in_tag_ids( $product_id, $tag_ids ): bool {
		$tag_ids = array_map( 'intval', (array) $tag_ids );

		if ( empty( $tag_ids ) ) return false;

		if ( function_exists('wc_get_product') ) {
			$product = wc_get_product( $product_id );
			if ( $product && $product->is_type( 'variation' ) ) {
				$product_id = $product->get_parent_id();
			}
		}

		if ( function_exists('wc_get_product_term_ids') ) {
			$term_ids = wc_get_product_term_ids( $product_id, 'product_tag' );
		} else {
			$terms = get_the_terms( $product_id, 'product_tag' );

			if ( empty( $terms ) || is_wp_error( $terms ) ) return false;

			$term_ids = array_map( fn($t) => (int) $t->term_id, $terms );
		}
		return !empty( array_intersect( $tag_ids, $term_ids ) );
	}
}