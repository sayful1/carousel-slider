<?php

namespace CarouselSlider\DataStores;

defined( 'ABSPATH' ) || die;

class Product_Carousel_Data_Store extends Data_Store_Base {
	/**
	 * Meta key to property
	 *
	 * @var string[]
	 */
	protected $meta_key_to_props = [
		'_product_query_type'        => 'query_type',
		'_product_query'             => 'query',
		'_product_categories'        => 'categories',
		'_product_tags'              => 'tags',
		'_product_in'                => 'ids_in',
		'_products_per_page'         => 'per_page',
		'_product_title'             => 'show_title',
		'_product_rating'            => 'show_rating',
		'_product_price'             => 'show_price',
		'_product_cart_button'       => 'show_cart_button',
		'_product_onsale'            => 'show_on_sale_tag',
		'_product_wishlist'          => 'show_wishlist',
		'_product_quick_view'        => 'show_quick_view',
		'_product_title_color'       => 'title_color',
		'_product_button_bg_color'   => 'button_background_color',
		'_product_button_text_color' => 'button_text_color',
	];
}
