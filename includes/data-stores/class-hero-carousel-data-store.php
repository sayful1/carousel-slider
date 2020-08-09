<?php

namespace CarouselSlider\DataStores;

defined( 'ABSPATH' ) || die;

class Hero_Carousel_Data_Store extends Data_Store_Base {
	/**
	 * Meta key to property
	 *
	 * @var string[]
	 */
	protected $meta_key_to_props = [
		'_content_slider'          => 'content',
		'_content_slider_settings' => 'settings',
	];
}
