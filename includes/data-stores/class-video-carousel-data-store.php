<?php

namespace CarouselSlider\DataStores;

defined( 'ABSPATH' ) || die;

class Video_Carousel_Data_Store extends Data_Store_Base {
	/**
	 * Meta key to property
	 *
	 * @var string[]
	 */
	protected $meta_key_to_props = [
		'_video_url' => 'urls'
	];
}
