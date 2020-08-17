<?php

namespace CarouselSlider\DataStores;

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
}
