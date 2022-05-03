<?php

namespace CarouselSlider;

use CarouselSlider\Interfaces\SliderViewInterface;
use WP_Error;
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Helper class
 */
class Helper extends ViewHelper {

	/**
	 * Get sliders
	 *
	 * @param array $args Optional arguments.
	 *
	 * @return WP_Post[]|int[] Array of post objects or post IDs.
	 */
	public static function get_sliders( array $args = [] ): array {
		$args = wp_parse_args(
			$args,
			[
				'post_status'    => 'publish',
				'posts_per_page' => - 1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			]
		);

		$args['post_type'] = CAROUSEL_SLIDER_POST_TYPE;

		return get_posts( $args );
	}

	/**
	 * Get setting
	 *
	 * @param string $key The setting key.
	 * @param mixed  $default Setting default value.
	 *
	 * @return mixed|null
	 */
	public static function get_setting( string $key, $default = null ) {
		$default_options = [
			'load_scripts'                        => 'optimized',
			'show_structured_data'                => '1',
			'woocommerce_shop_loop_item_template' => 'v1-compatibility',
		];
		$settings        = (array) get_option( 'carousel_slider_settings' );
		$settings        = wp_parse_args( $settings, $default_options );

		return $settings[ $key ] ?? $default;
	}

	/**
	 * Get carousel slider available slide type
	 *
	 * @return array
	 */
	public static function get_slide_types(): array {
		return apply_filters(
			'carousel_slider_slide_type',
			[
				'image-carousel'     => __( 'Image Carousel', 'carousel-slider' ),
				'image-carousel-url' => __( 'Image Carousel (URL)', 'carousel-slider' ),
				'post-carousel'      => __( 'Post Carousel', 'carousel-slider' ),
				'product-carousel'   => __( 'Product Carousel', 'carousel-slider' ),
				'video-carousel'     => __( 'Video Carousel', 'carousel-slider' ),
				'hero-banner-slider' => __( 'Hero Carousel', 'carousel-slider' ),
			]
		);
	}

	/**
	 * Get slider view
	 *
	 * @param string $key The slider type slug.
	 *
	 * @return false|SliderViewInterface
	 */
	public static function get_slider_view( string $key ) {
		$views = apply_filters( 'carousel_slider/register_view', [] );

		return $views[ $key ] ?? false;
	}

	/**
	 * Get default settings
	 *
	 * @return array
	 */
	public static function get_default_settings(): array {
		return apply_filters(
			'carousel_slider_default_settings',
			[
				'product_title_color'       => '#323232',
				'product_button_bg_color'   => '#00d1b2',
				'product_button_text_color' => '#f1f1f1',
				'nav_color'                 => '#f1f1f1',
				'nav_active_color'          => '#00d1b2',
				'margin_right'              => 10,
				'lazy_load_image'           => 'off',
			]
		);
	}

	/**
	 * Get default setting
	 *
	 * @param string $key The setting key.
	 * @param mixed  $default Default value.
	 *
	 * @return mixed|null
	 */
	public static function get_default_setting( string $key, $default = null ) {
		$settings = self::get_default_settings();

		return $settings[ $key ] ?? $default;
	}

	/**
	 * Get available image sizes
	 *
	 * @return array
	 */
	public static function get_available_image_sizes(): array {
		global $_wp_additional_image_sizes;

		$sizes = [];
		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, [ 'thumbnail', 'medium', 'medium_large', 'large' ], true ) ) {

				$width  = get_option( "{$_size}_size_w" );
				$height = get_option( "{$_size}_size_h" );
				$crop   = get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[ $_size ] = sprintf( '%s - %s:%sx%s', $_size, $crop, $width, $height );

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$width  = $_wp_additional_image_sizes[ $_size ]['width'];
				$height = $_wp_additional_image_sizes[ $_size ]['height'];
				$crop   = $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[ $_size ] = sprintf( '%s - %s:%sx%s', $_size, $crop, $width, $height );
			}
		}

		return array_merge( $sizes, [ 'full' => 'original uploaded image' ] );
	}

	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 */
	public static function is_woocommerce_active(): bool {
		return in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true ) ||
			defined( 'WC_VERSION' ) ||
			defined( 'WOOCOMMERCE_VERSION' );
	}

	/**
	 * Creates Carousel Slider test page
	 *
	 * @param array $ids The sliders ids.
	 *
	 * @return int|WP_Error
	 */
	public static function create_test_page( array $ids = [] ) {
		$page_path    = 'carousel-slider-test';
		$page_title   = __( 'Carousel Slider Test', 'carousel-slider' );
		$page_content = '';

		if ( empty( $ids ) ) {
			$ids = self::get_sliders();
		}

		foreach ( $ids as $id ) {
			$_post         = get_post( $id );
			$page_content .= '<!-- wp:heading {"level":4} --><h4>' . $_post->post_title . '</h4><!-- /wp:heading -->';
			$page_content .= '<!-- wp:carousel-slider/slider {"sliderID":' . $id . ',"sliderName":"' . $_post->post_title . ' ( ID: ' . $id . ' )"} -->';
			$page_content .= '<div class="wp-block-carousel-slider-slider">[carousel_slide id=\'' . $id . '\']</div>';
			$page_content .= '<!-- /wp:carousel-slider/slider -->';
			$page_content .= '<!-- wp:spacer {"height":100} --><div style="height:100px" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer -->';
		}

		// Check that the page doesn't exist already.
		$_page     = get_page_by_path( $page_path );
		$page_data = [
			'post_content'   => $page_content,
			'post_name'      => $page_path,
			'post_title'     => $page_title,
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'ping_status'    => 'closed',
			'comment_status' => 'closed',
		];

		if ( $_page instanceof WP_Post ) {
			$page_data['ID'] = $_page->ID;

			return wp_update_post( $page_data );
		}

		return wp_insert_post( $page_data );
	}

	/**
	 * What type of request is this?
	 *
	 * @param string $type admin, ajax, rest, cron or frontend.
	 *
	 * @return bool
	 */
	public static function is_request( string $type ): bool {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'rest':
				return defined( 'REST_REQUEST' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}

		return false;
	}

	/**
	 * Get preview link
	 *
	 * @param WP_Post $post The WP_Post object.
	 *
	 * @return string
	 */
	public static function get_preview_link( WP_Post $post ): string {
		$args = [
			'carousel_slider_preview' => true,
			'carousel_slider_iframe'  => true,
			'slider_id'               => $post->ID,
		];

		return add_query_arg( $args, site_url( '/' ) );
	}

	/**
	 * Print internal content (not user input) without escaping.
	 *
	 * @param string $string The string to be print.
	 */
	public static function print_unescaped_internal_string( string $string ) {
		echo $string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
