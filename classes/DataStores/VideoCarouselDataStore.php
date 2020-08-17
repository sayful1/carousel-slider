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
