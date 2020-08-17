<?php

namespace CarouselSlider\DataStores;

defined( 'ABSPATH' ) || die;

class ImageCarouselDataStore extends DataStoreBase {
	/**
	 * Meta key to property
	 *
	 * @var string[]
	 */
	protected $meta_key_to_props = [
		'_images_urls'             => 'external_image_url',
		'_wpdh_image_ids'          => 'image_ids',
		'_show_attachment_title'   => 'show_title',
		'_show_attachment_caption' => 'show_caption',
		'_image_target'            => 'target',
		'_image_lightbox'          => 'show_lightbox',
	];

	/**
	 * Read data
	 *
	 * @param array|int $data
	 *
	 * @return array
	 */
	public function read( $data ) {
		$meta_data = parent::read( $data );

		foreach ( $this->meta_key_to_props as $key => $prop ) {
			$meta_data[ $key ] = get_post_meta( intval( $data ), $key, true );
		}

		return $meta_data;
	}
}
