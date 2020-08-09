<?php

namespace CarouselSlider\DataStores;

defined( 'ABSPATH' ) || die;

class Post_Carousel_Data_Store extends Data_Store_Base {
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
}
