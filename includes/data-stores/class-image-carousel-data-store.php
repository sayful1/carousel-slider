<?php

namespace CarouselSlider\DataStores;

defined( 'ABSPATH' ) || die;

class Image_Carousel_Data_Store extends Data_Store_Base {
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
}
