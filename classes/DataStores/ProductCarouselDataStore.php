<?php

namespace CarouselSlider\DataStores;

defined( 'ABSPATH' ) || die;

class ProductCarouselDataStore extends DataStoreBase {
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
