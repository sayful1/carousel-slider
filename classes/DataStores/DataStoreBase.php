<?php

namespace CarouselSlider\DataStores;

use CarouselSlider\Abstracts\SliderSettings;
use CarouselSlider\Interfaces\DataStoreInterface;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Utils;
use WP_Error;
use WP_Post;

defined( 'ABSPATH' ) || die;

class DataStoreBase implements DataStoreInterface {
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
		'_infinity_loop',
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

		$meta_data = [];

		foreach ( $this->meta_keys as $meta_key ) {
			$value = get_post_meta( $post->ID, $meta_key, true );

			if ( $meta_key == '_margin_right' && $value == 'zero' ) {
				$value = 0;
			}

			$meta_data[ $meta_key ] = $value;
		}

		return static::build_slider_settings( $meta_data );
	}

	/**
	 * Create new slider
	 *
	 * @param array $data
	 *
	 * @return mixed|void
	 */
	public function create( array $data ) {
		$this->update( $data );
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
	 * @param int $data
	 * @param bool $force_delete
	 *
	 * @return bool
	 */
	public function delete( $data, $force_delete = false ) {
		if ( ! is_numeric( $data ) ) {
			return false;
		}

		$post = get_post( $data );
		if ( ! $post instanceof WP_Post ) {
			return false;
		}

		if ( $post->post_type != $this->meta_type ) {
			return false;
		}

		return (bool) wp_delete_post( $data, $force_delete );
	}

	/**
	 * Map data
	 *
	 * @param array $data
	 *
	 * @return SliderSettings
	 */
	private static function build_slider_settings( array $data ) {
		$settings = [
			'type'               => static::get_props( $data, '_slide_type', 'image-carousel' ),
			'image_size'         => static::get_props( $data, '_image_size', 'medium_large' ),
			'lazy_load_image'    => Validate::checked( static::get_props( $data, '_lazy_load_image', true ) ),
			'gutter'             => intval( static::get_props( $data, '_margin_right', 10 ) ),
			'infinity_loop'      => Validate::checked( static::get_props( $data, '_infinity_loop', true ) ),
			'stage_padding'      => intval( static::get_props( $data, '_stage_padding', 0 ) ),
			'auto_width'         => Validate::checked( static::get_props( $data, '_auto_width' ) ),
			// Responsive settings meta key
			'items_full_hd'      => intval( static::get_props( $data, '_items' ) ),
			'items_widescreen'   => intval( static::get_props( $data, '_items_desktop' ) ),
			'items_desktop'      => intval( static::get_props( $data, '_items_small_desktop' ) ),
			'items_tablet'       => intval( static::get_props( $data, '_items_portrait_tablet' ) ),
			'items_small_tablet' => intval( static::get_props( $data, '_items_small_portrait_tablet' ) ),
			'items_mobile'       => intval( static::get_props( $data, '_items_portrait_mobile' ) ),
			// Autoplay settings meta key
			'autoplay'           => Validate::checked( static::get_props( $data, '_autoplay' ) ),
			'autoplay_pause'     => Validate::checked( static::get_props( $data, '_autoplay_pause' ) ),
			'autoplay_timeout'   => intval( static::get_props( $data, '_autoplay_timeout' ) ),
			'autoplay_speed'     => intval( static::get_props( $data, '_autoplay_speed' ) ),
			// Navigation settings meta key
			'show_arrow_nav'     => static::get_props( $data, '_nav_button' ),
			'arrow_position'     => static::get_props( $data, '_arrow_position' ),
			'arrow_size'         => intval( static::get_props( $data, '_arrow_size' ) ),
			'show_dot_nav'       => static::get_props( $data, '_dot_nav' ),
			'dot_size'           => intval( static::get_props( $data, '_bullet_size' ) ),
			'dot_position'       => static::get_props( $data, '_bullet_position' ),
			'dot_shape'          => static::get_props( $data, '_bullet_shape' ),
			'arrow_step'         => static::get_props( $data, '_slide_by' ),
			'nav_color'          => static::get_props( $data, '_nav_color' ),
			'nav_active_color'   => static::get_props( $data, '_nav_active_color' ),
		];

		return new SliderSettings( $settings );
	}

	/**
	 * Get props from item
	 *
	 * @param array $data
	 * @param string $key
	 * @param string $default
	 *
	 * @return mixed
	 */
	protected static function get_props( array $data, $key, $default = '' ) {
		return isset( $data[ $key ] ) ? $data[ $key ] : $default;
	}
}
