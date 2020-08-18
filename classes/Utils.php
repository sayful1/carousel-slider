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
	 * @return WP_Post[]
	 */
	public static function get_all_sliders() {
		return get_posts( [
			'post_type'      => static::POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		] );
	}
}
