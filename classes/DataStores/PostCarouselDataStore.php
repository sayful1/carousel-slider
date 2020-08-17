<?php

namespace CarouselSlider\DataStores;

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
