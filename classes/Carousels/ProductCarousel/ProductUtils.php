<?php

namespace CarouselSlider\Carousels\ProductCarousel;

use CarouselSlider\Abstracts\SliderSettings;
use CarouselSlider\Utils;
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
	 * @param int $slider_id
	 *
	 * @return array|WC_Product[]
	 */
	public static function get_products( $slider_id ) {
		/** @var SliderSettings $setting */
		$setting = Utils::get_slider( $slider_id );

		$query_type = $setting->get_prop( 'query_type', 'query_product' );
		$query      = $setting->get_prop( 'query' );

		$args = static::parse_args( [
			'limit' => $setting->get_prop( 'per_page', 12 ),
		] );

		if ( $query_type == 'specific_products' ) {
			$args['include'] = (array) $setting->get_prop( 'ids_in', [] );
		}

		if ( $query_type == 'product_categories' ) {
			$categories       = (array) $setting->get_prop( 'categories', [] );
			$args['category'] = static::format_term_for_query( $categories, 'product_cat' );
		}

		if ( $query_type == 'product_tags' ) {
			$tags        = (array) $setting->get_prop( 'tags', [] );
			$args['tag'] = static::format_term_for_query( $tags, 'product_tag' );
		}

		if ( $query_type == 'query_product' ) {
			// Featured
			if ( $query == 'featured' ) {
				$args['featured'] = true;
			}

			// Best selling
			if ( $query == 'best_selling' ) {
				$args['order']    = 'DESC';
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'total_sales';
			}

			// Recent products
			if ( $query == 'recent' ) {
				$args['order']   = 'DESC';
				$args['orderby'] = 'date';
			}

			if ( $query == 'sale' ) {
				$args['include'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
			}

			if ( $query == 'top_rated' ) {
				$args['order']    = 'DESC';
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_wc_average_rating';
			}

		}

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

	/**
	 * Format term slug
	 *
	 * @param array $tags
	 * @param string $taxonomy
	 *
	 * @return array
	 */
	protected static function format_term_for_query( array $tags, $taxonomy ) {
		$ids = [];
		foreach ( $tags as $index => $tag ) {
			if ( is_numeric( $tag ) ) {
				$ids[] = intval( $tag );
				unset( $tags[ $index ] );
			}
		}
		if ( count( $ids ) ) {
			$terms = get_terms( [ 'taxonomy' => $taxonomy, 'include' => $ids ] );
			$slugs = wp_list_pluck( $terms, 'slug' );
			$tags  = array_merge( $slugs, array_values( $tags ) );
		}

		return $tags;
	}

	/**
	 * Get product quick view url
	 *
	 * @param int $product_id
	 *
	 * @return string
	 */
	public static function get_product_quick_view_url( $product_id ) {
		$args = array(
			'action'     => 'carousel_slider_quick_view',
			'ajax'       => 'true',
			'product_id' => $product_id,
		);
		$url  = add_query_arg( $args, admin_url( 'admin-ajax.php' ) );

		return wp_nonce_url( $url, 'carousel_slider_quick_view' );
	}
}
