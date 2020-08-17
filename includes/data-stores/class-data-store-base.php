<?php

namespace CarouselSlider\DataStores;

defined( 'ABSPATH' ) || die;

class Data_Store_Base {
	/**
	 * Internal meta type used to store order data.
	 *
	 * @var string
	 */
	protected $meta_type = 'carousels';

	/**
	 * Data stored in meta keys, but not considered "meta".
	 *
	 * @var array
	 */
	protected $meta_keys = [
		'_slide_type',
		// General Settings meta keys
		'_image_size',
		'_lazy_load_image',
		'_margin_right',
		'_inifnity_loop',
		'_stage_padding',
		'_auto_width',
		// Responsive settings meta key
		'_items',
		'_items_desktop',
		'_items_small_desktop',
		'_items_portrait_tablet',
		'_items_small_portrait_tablet',
		'_items_portrait_mobile',
		// Autoplay settings meta key
		'_autoplay',
		'_autoplay_pause',
		'_autoplay_timeout',
		'_autoplay_speed',
		// Navigation settings meta key
		'_nav_button',
		'_dot_nav',
		'_slide_by',
		'_nav_color',
		'_nav_active_color',
		'_arrow_position',
		'_arrow_size',
		'_bullet_size',
		'_bullet_position',
		'_bullet_shape',
		// Video carousel
		'_video_url',
		// Product carousel
		'_product_query_type',
		'_product_query',
		'_product_categories',
		'_product_tags',
		'_product_in',
		'_products_per_page',
		'_product_title',
		'_product_rating',
		'_product_price',
		'_product_cart_button',
		'_product_onsale',
		'_product_wishlist',
		'_product_quick_view',
		'_product_title_color',
		'_product_button_bg_color',
		'_product_button_text_color',
		// Post Carousel
		'_post_query_type',
		'_post_date_after',
		'_post_date_before',
		'_post_categories',
		'_post_tags',
		'_post_in',
		'_posts_per_page',
		'_post_order',
		'_post_orderby',
		// Image carousel from URL
		'_images_urls',
		// Image carousel from Media Image
		'_wpdh_image_ids',
		'_show_attachment_title',
		'_show_attachment_caption',
		'_image_target',
		'_image_lightbox',
		// Hero banner carousel
		'_content_slider',
		'_content_slider_settings',
	];

	/**
	 * Read data
	 *
	 * @param array|int $data
	 *
	 * @return array
	 */
	public function read( $data ) {
		$meta_data = [];

		if ( is_array( $data ) ) {
			foreach ( $data as $key => $value ) {
				if ( ! in_array( $key, $this->meta_keys ) ) {
					continue;
				}
				$meta_data[ $key ] = $value;
			}
		}

		if ( is_numeric( $data ) ) {
			foreach ( $this->meta_keys as $meta_key ) {
				$value = get_post_meta( intval( $data ), $meta_key, true );
				if ( $meta_key == '_margin_right' && $value == 'zero' ) {
					$value = 0;
				}

				$meta_data[ $meta_key ] = $value;
			}
		}

		return $meta_data;
	}

	/**
	 * Update data
	 *
	 * @param array $data
	 */
	public function update( array $data ) {
		$id   = isset( $data['id'] ) ? absint( $data['id'] ) : 0;
		$type = isset( $data['_slide_type'] ) ? sanitize_text_field( $data['_slide_type'] ) : '';

		update_post_meta( $id, '_slide_type', $type );
		update_post_meta( $id, '_plugin_version', CAROUSEL_SLIDER_VERSION );

		foreach ( $data as $meta_key => $meta_value ) {
			if ( ! in_array( $meta_key, $this->meta_keys ) ) {
				continue;
			}
			if ( $meta_key == '_margin_right' && $meta_value == 0 ) {
				$meta_value = 'zero';
			}
			$value = map_deep( $meta_value, 'sanitize_text_field' );
			update_post_meta( $id, $meta_key, $value );
		}
	}

	/**
	 * Delete a carousel
	 *
	 * @param int  $data
	 * @param bool $force_delete
	 *
	 * @return bool
	 */
	public function delete( $data, $force_delete = false ) {
		if ( ! is_numeric( $data ) ) {
			return false;
		}

		$post = get_post( $data );
		if ( ! $post instanceof \WP_Post ) {
			return false;
		}

		if ( $post->post_type != $this->meta_type ) {
			return false;
		}

		return (bool) wp_delete_post( $data, $force_delete );
	}

	/**
	 * Get store class
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public static function get_store( $key ) {
		$stores = [
			'hero'    => Hero_Carousel_Data_Store::class,
			'image'   => Image_Carousel_Data_Store::class,
			'post'    => Post_Carousel_Data_Store::class,
			'product' => Product_Carousel_Data_Store::class,
			'video'   => Video_Carousel_Data_Store::class,
		];

		return $stores[ $key ];
	}

	/**
	 * Sanitize color
	 *
	 * @param string $color
	 *
	 * @return string
	 */
	public static function sanitize_color( $color ) {
		return carousel_slider_sanitize_color( $color );
	}
}
