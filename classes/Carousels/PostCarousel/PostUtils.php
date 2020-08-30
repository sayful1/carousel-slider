<?php

namespace CarouselSlider\Carousels\PostCarousel;

use CarouselSlider\Abstracts\SliderSettings;
use CarouselSlider\Utils;
use WP_Post;

class PostUtils {
	/**
	 * Get posts
	 *
	 * @param $slider_id
	 *
	 * @return array|WP_Post[]
	 */
	public static function get_posts( $slider_id ) {
		/** @var SliderSettings $setting */
		$setting    = Utils::get_slider( $slider_id );
		$query_type = $setting->get_prop( 'query_type', 'latest_posts' );

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'order'          => $setting->get_prop( 'order', 'DESC' ),
			'orderby'        => $setting->get_prop( 'order_by', 'ID' ),
			'posts_per_page' => $setting->get_prop( 'per_page', 12 )
		);

		if ( $query_type == 'specific_posts' ) {
			$args['post_in'] = $setting->get_prop( 'ids_in', [] );
		}

		if ( $query_type == 'post_categories' ) {
			$args['category__in'] = $setting->get_prop( 'categories', [] );
		}

		if ( $query_type == 'post_tags' ) {
			$args['tag__in'] = $setting->get_prop( 'tags', [] );
		}

		if ( $query_type == 'date_range' ) {
			$date_from  = $setting->get_prop( 'date_from' );
			$date_to    = $setting->get_prop( 'date_to' );
			$date_query = [ 'inclusive' => true ];
			if ( ! empty( $date_from ) ) {
				$date_query['after'] = $date_from;
			}
			if ( ! empty( $date_to ) ) {
				$date_query['before'] = $date_to;
			}

			$args['date_query'] = $date_query;
		}

		return get_posts( $args );
	}
}
