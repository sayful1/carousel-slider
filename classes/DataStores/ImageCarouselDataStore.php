<?php

namespace CarouselSlider\DataStores;

use CarouselSlider\Abstracts\SliderSettings;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Utils;
use WP_Error;
use WP_Post;

defined( 'ABSPATH' ) || die;

class ImageCarouselDataStore extends DataStoreBase {
	/**
	 * Meta key to property
	 *
	 * @var string[]
	 */
	protected $meta_key_to_props = [
		'_images_urls'             => 'external_image_url',
		'_image_ids'               => 'image_ids',
		'_show_attachment_title'   => 'show_title',
		'_show_attachment_caption' => 'show_caption',
		'_image_lightbox'          => 'show_lightbox',
		'_image_target'            => 'target',
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
			$value = get_post_meta( $post->ID, $key, true );
			if ( in_array( $prop, [ 'show_title', 'show_caption', 'show_lightbox' ] ) ) {
				$value = Validate::checked( $value );
			}

			if ( $prop == 'image_ids' ) {
				if ( is_string( $value ) ) {
					// Convert to array
					$value = explode( ',', trim( $value, ',' ) );
					// Map as integer value
					$value = array_filter( array_map( 'intval', $value ) );
				}
			}

			if ( $prop == 'external_image_url' ) {
				$value = static::read_external_image_url( $value );
			}

			$settings->set_prop( $prop, $value );
		}

		return $settings;
	}

	/**
	 * Read external image url
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public static function read_external_image_url( $data ) {
		$meta_data = [];
		if ( is_array( $data ) && count( $data ) ) {
			foreach ( $data as $index => $value ) {
				if ( ! ( isset( $value['url'] ) && filter_var( $value['url'], FILTER_VALIDATE_URL ) ) ) {
					continue;
				}
				$meta_data[] = $value;
			}
		}

		return $meta_data;
	}

	/**
	 * Sanitize external urls
	 *
	 * @param array $urls
	 *
	 * @return array
	 */
	public static function sanitize_external_urls( array $urls = [] ) {
		$default = [ 'url' => '', 'title' => '', 'caption' => '', 'alt' => '', 'link_url' => '' ];
		$data    = [];
		foreach ( $urls as $url ) {
			$item = wp_parse_args( $url, $default );
			if ( ! Validate::url( $item['url'] ) ) {
				continue;
			}
			$data[] = array(
				'url'      => esc_url_raw( $item['url'] ),
				'title'    => sanitize_text_field( $item['title'] ),
				'caption'  => sanitize_text_field( $item['caption'] ),
				'alt'      => sanitize_text_field( $item['alt'] ),
				'link_url' => esc_url_raw( $item['link_url'] ),
			);
		}

		return $data;
	}
}
