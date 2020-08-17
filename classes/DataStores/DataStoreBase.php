<?php

namespace CarouselSlider\DataStores;

use CarouselSlider\Interfaces\DataStoreInterface;
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
}
