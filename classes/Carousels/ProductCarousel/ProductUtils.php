<?php

namespace CarouselSlider\Carousels\ProductCarousel;

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
	 * Get products
	 *
	 * @param array $args
	 *
	 * @return array|WC_Product[]
	 */
	public static function get_products( array $args = [] ) {
		return wc_get_products( static::parse_args( $args ) );
	}

	/**
	 * Get recent products
	 *
	 * @param array $args
	 *
	 * @return array|WC_Product[]
	 */
	public static function recent_products( array $args = [] ) {
		return static::get_products( $args );
	}

	/**
	 * Get sale products
	 *
	 * @param array $args
	 *
	 * @return array|WC_Product[]
	 */
	public static function sale_products( array $args = [] ) {
		$args['include'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );

		return static::get_products( $args );
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
	 * @param string[] $categories Array of categories slug or categories id
	 * @param int $limit
	 *
	 * @return array|WC_Product[]
	 */
	public static function products_by_categories( array $categories = array(), $limit = 12 ) {
		$ids = [];
		foreach ( $categories as $index => $category ) {
			if ( is_numeric( $category ) ) {
				$ids[] = intval( $category );
				unset( $categories[ $index ] );
			}
		}
		if ( count( $ids ) ) {
			$terms      = get_terms( [ 'taxonomy' => 'product_cat', 'include' => $ids ] );
			$slugs      = wp_list_pluck( $terms, 'slug' );
			$categories = array_merge( $slugs, array_values( $categories ) );
		}

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
	public static function products_by_tags( array $tags = array(), $limit = 12 ) {
		$ids = [];
		foreach ( $tags as $index => $tag ) {
			if ( is_numeric( $tag ) ) {
				$ids[] = intval( $tag );
				unset( $tags[ $index ] );
			}
		}
		if ( count( $ids ) ) {
			$terms = get_terms( [ 'taxonomy' => 'product_tag', 'include' => $ids ] );
			$slugs = wp_list_pluck( $terms, 'slug' );
			$tags  = array_merge( $slugs, array_values( $tags ) );
		}

		$args        = static::parse_args( [ 'limit' => $limit ] );
		$args['tag'] = $tags;

		return wc_get_products( $args );
	}

	/**
	 * List all (or limited) product categories.
	 *
	 * @param array $args
	 *
	 * @return array|WP_Term[]
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
