<?php

namespace CarouselSlider\DataStores;

use CarouselSlider\Abstracts\SliderSettings;
use CarouselSlider\Carousels\VideoCarousel\VideoUtils;
use CarouselSlider\Utils;
use WP_Error;
use WP_Post;

defined( 'ABSPATH' ) || die;

class VideoCarouselDataStore extends DataStoreBase {
	/**
	 * Meta key to property
	 *
	 * @var string[]
	 */
	protected $meta_key_to_props = [
		'_video_url' => 'urls'
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

		$url = get_post_meta( $post->ID, '_video_url', true );
		if ( is_string( $url ) ) {
			$url = explode( ',', $url );
		}

		$url = VideoUtils::get_video_url( $url );

		$settings->set_prop( 'video_urls', $url );

		return $settings;
	}
}
