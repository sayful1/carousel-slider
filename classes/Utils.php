<?php

namespace CarouselSlider;

use CarouselSlider\DataStores\DataStoreBase;
use CarouselSlider\DataStores\HeroCarouselDataStore;
use CarouselSlider\DataStores\ImageCarouselDataStore;
use CarouselSlider\DataStores\PostCarouselDataStore;
use CarouselSlider\DataStores\ProductCarouselDataStore;
use CarouselSlider\DataStores\VideoCarouselDataStore;
use WP_Post;

defined( 'ABSPATH' ) || exit;

class Utils {
	/**
	 * Post type name
	 */
	const POST_TYPE = 'carousels';

	/**
	 * Get store class
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public static function get_store( $key ) {
		$stores = [
			'hero-banner-slider' => HeroCarouselDataStore::class,
			'image-carousel'     => ImageCarouselDataStore::class,
			'image-carousel-url' => ImageCarouselDataStore::class,
			'post-carousel'      => PostCarouselDataStore::class,
			'product-carousel'   => ProductCarouselDataStore::class,
			'video-carousel'     => VideoCarouselDataStore::class,
		];

		return isset( $stores[ $key ] ) ? $stores[ $key ] : DataStoreBase::class;
	}

	/**
	 * Get slider
	 *
	 * @param int $slider_id
	 *
	 * @return mixed
	 */
	public static function get_slider( $slider_id ) {
		$type  = get_post_meta( intval( $slider_id ), '_slide_type', true );
		$class = static::get_store( $type );

		return ( new $class )->read( $slider_id );
	}

	/**
	 * Get all sliders
	 *
	 * @param array $args
	 *
	 * @return WP_Post[]
	 */
	public static function get_all_sliders( array $args = [] ) {
		$args = wp_parse_args( $args, [
			'posts_per_page' => - 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_status'    => 'publish',
		] );

		$args['post_type'] = static::POST_TYPE;

		return get_posts( $args );
	}

	/**
	 * Check if WooCommerce active
	 *
	 * @return bool
	 */
	public static function is_woocommerce_active() {
		if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ) ) ) {
			return true;
		}

		if ( defined( 'WC_VERSION' ) || defined( 'WOOCOMMERCE_VERSION' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get image sizes
	 *
	 * @return array
	 */
	public static function get_image_sizes() {
		$default_sizes    = array( 'thumbnail', 'medium', 'medium_large', 'large' );
		$additional_sizes = wp_get_additional_image_sizes();

		$sizes = array();
		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, $default_sizes ) ) {
				$width  = get_option( "{$_size}_size_w" );
				$height = get_option( "{$_size}_size_h" );
				$crop   = (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[ $_size ] = "{$_size} - $crop:{$width}x{$height}";
			}

			if ( isset( $additional_sizes[ $_size ] ) ) {
				$width  = $additional_sizes[ $_size ]['width'];
				$height = $additional_sizes[ $_size ]['height'];
				$crop   = $additional_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[ $_size ] = "{$_size} - $crop:{$width}x{$height}";
			}
		}

		return array_merge( $sizes, array( 'full' => 'original uploaded image' ) );
	}

	/**
	 * Convert array to HTML attribute
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	public static function array_to_attributes( array $data ) {
		$attributes = [];
		foreach ( $data as $key => $value ) {
			if ( empty( $value ) && 'value' !== $key ) {
				continue;
			}
			if ( in_array( $key, array( 'required', 'checked', 'multiple' ) ) ) {
				$attributes[] = $value ? esc_attr( $key ) : '';
				continue;
			}

			if ( is_array( $value ) ) {
				$attributes[] = esc_attr( $key ) . '=' . "'" . wp_json_encode( $value ) . "'";
				$attributes[] = sprintf( "%s='%s'", esc_attr( $key ), wp_json_encode( $value ) );
				continue;
			}

			// If boolean value
			if ( is_bool( $value ) ) {
				$value = $value ? 'true' : 'false';
			}

			$attributes[] = sprintf( '%s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}

		return join( " ", $attributes );
	}
}
