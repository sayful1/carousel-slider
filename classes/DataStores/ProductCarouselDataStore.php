<?php

namespace CarouselSlider\DataStores;

use CarouselSlider\Abstracts\SliderSettings;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Utils;
use WP_Error;
use WP_Post;

defined( 'ABSPATH' ) || die;

class ProductCarouselDataStore extends DataStoreBase {
	/**
	 * Meta key to property
	 *
	 * @var string[]
	 */
	protected $meta_key_to_props = [
		'_product_query_type'        => 'query_type',
		'_product_query'             => 'query',
		'_product_categories'        => 'categories',
		'_product_tags'              => 'tags',
		'_product_in'                => 'ids_in',
		'_products_per_page'         => 'per_page',
		'_product_title'             => 'show_title',
		'_product_rating'            => 'show_rating',
		'_product_price'             => 'show_price',
		'_product_cart_button'       => 'show_cart_button',
		'_product_onsale'            => 'show_on_sale_tag',
		'_product_wishlist'          => 'show_wishlist',
		'_product_quick_view'        => 'show_quick_view',
		'_product_title_color'       => 'title_color',
		'_product_button_bg_color'   => 'button_background_color',
		'_product_button_text_color' => 'button_text_color',
	];

	/**
	 * Read data
	 *
	 * @param WP_Post|int $post
	 *
	 * @return SliderSettings|WP_Error
	 */
	public function read( $post ) {
		$post = get_post( $post );
		if ( ! ( $post instanceof WP_Post && $post->post_type == Utils::POST_TYPE ) ) {
			return new WP_Error( 'no_slider_found', __( 'No slider found', 'carousel-slider' ) );
		}

		$settings = parent::read( $post );

		foreach ( $this->meta_key_to_props as $key => $prop ) {
			$settings[ $prop ] = get_post_meta( $post->ID, $key, true );

			// Fix typo mistake for query type
			if ( $prop == 'query_type' ) {
				$settings[ $prop ] = str_replace( 'query_porduct', 'query_product', $settings[ $prop ] );
			}

			if ( strpos( $prop, 'show_' ) !== false ) {
				$settings[ $prop ] = Validate::checked( $settings[ $prop ] );
			}

			if ( $prop == 'per_page' ) {
				$settings[ $prop ] = intval( $settings[ $prop ] );
			}

			if ( in_array( $prop, [ 'ids_in', 'categories', 'tags' ] ) && is_string( $settings[ $prop ] ) ) {
				$ids_in = explode( ',', $settings[ $prop ] );

				$settings[ $prop ] = array_filter( array_map( 'intval', $ids_in ) );
			}
		}

		return $settings;
	}
}
