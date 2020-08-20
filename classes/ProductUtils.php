<?php

namespace CarouselSlider;

use WC_Product;
use WP_Term;

defined( 'ABSPATH' ) || exit;

class ProductUtils {

	/**
	 * Parse arguments
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private static function parse_args( array $args = [] ) {
		$args = wp_parse_args( $args, array(
			'limit'      => 12,
			'order'      => 'DESC',
			'orderby'    => 'date',
			'visibility' => 'catalog',
			'paginate'   => false,
			'page'       => 1,
			'return'     => 'objects',
		) );

		return $args;
	}

	/**
	 * Get recent products
	 *
	 * @param array $args
	 *
	 * @return array|WC_Product[]
	 */
	public static function recent_products( array $args = [] ) {
		return wc_get_products( static::parse_args( $args ) );
	}

	/**
	 * Get best selling products
	 *
	 * @param array $args
	 *
	 * @return array|WC_Product[]
	 */
	public static function best_selling_products( array $args = [] ) {
		$args = static::parse_args( $args );

		$args['order']    = 'DESC';
		$args['orderby']  = 'meta_value_num';
		$args['meta_key'] = 'total_sales';

		return wc_get_products( $args );
	}

	/**
	 * Get top rated products
	 *
	 * @param array $args
	 *
	 * @return array|WC_Product[]
	 */
	public static function top_rated_products( array $args = [] ) {
		$args = static::parse_args( $args );

		$args['order']    = 'DESC';
		$args['orderby']  = 'meta_value_num';
		$args['meta_key'] = '_wc_average_rating';

		return wc_get_products( $args );
	}

	/**
	 * Get featured products
	 *
	 * @param array $args
	 *
	 * @return array|WC_Product[]
	 */
	public static function featured_products( array $args = [] ) {
		$args['featured'] = true;
		$args             = static::parse_args( $args );

		return wc_get_products( $args );
	}

	/**
	 * Get products by categories slug
	 *
	 * @param string[] $categories Array of categories slug
	 * @param int $limit
	 *
	 * @return array|WC_Product[]
	 */
	public function products_by_categories( array $categories = array(), $limit = 12 ) {
		$args             = static::parse_args( [ 'limit' => $limit ] );
		$args['category'] = $categories;

		return wc_get_products( $args );
	}

	/**
	 * Get products by tags slug
	 *
	 * @param string[] $tags Array of tags slug
	 * @param int $limit
	 *
	 * @return array|WC_Product[]
	 */
	public function products_by_tags( array $tags = array(), $limit = 12 ) {
		$args        = static::parse_args( [ 'limit' => $limit ] );
		$args['tag'] = $tags;

		return wc_get_products( $args );
	}

	/**
	 * List all (or limited) product categories.
	 *
	 * @param array $args
	 *
	 * @return WP_Term[]
	 */
	public static function product_categories( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'hide_empty' => true,
			'orderby'    => 'name',
			'order'      => 'ASC',
		) );

		$args['taxonomy'] = 'product_cat';

		return get_terms( $args );
	}
}
