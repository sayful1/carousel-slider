<?php

namespace CarouselSlider\DataStores;

use CarouselSlider\Abstracts\SliderSettings;
use CarouselSlider\Utils;
use WP_Error;
use WP_Post;

defined( 'ABSPATH' ) || die;

class PostCarouselDataStore extends DataStoreBase {
	/**
	 * Meta key to property
	 *
	 * @var string[]
	 */
	protected $meta_key_to_props = [
		'_post_query_type'  => 'query_type',
		'_post_date_after'  => 'date_from',
		'_post_date_before' => 'date_to',
		'_post_categories'  => 'categories',
		'_post_tags'        => 'tags',
		'_post_in'          => 'ids_in',
		'_posts_per_page'   => 'per_page',
		'_post_order'       => 'order',
		'_post_orderby'     => 'order_by',
	];

	/**
	 * Read data
	 *
	 * @param array|int $post
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
