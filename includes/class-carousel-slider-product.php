<?php

use CarouselSlider\Carousels\ProductCarousel\ProductUtils;
use CarouselSlider\Supports\Validate;

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Carousel_Slider_Product' ) ) {

	class Carousel_Slider_Product {

		/**
		 * The instance of the class
		 *
		 * @var self
		 */
		private static $instance;

		/**
		 * Product carousel quick view
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				add_action( 'carousel_slider_after_shop_loop_item', [ self::$instance, 'quick_view_button' ], 10, 3 );
				add_action( 'carousel_slider_after_shop_loop_item', [ self::$instance, 'wish_list_button' ], 10, 3 );

				add_action( 'wp_ajax_carousel_slider_quick_view', [ self::$instance, 'quick_view' ] );
				add_action( 'wp_ajax_nopriv_carousel_slider_quick_view', array( self::$instance, 'quick_view' ) );
			}

			return self::$instance;
		}

		/**
		 * Show YITH Wishlist button on product slider
		 *
		 * @param WC_Product $product
		 * @param WP_Post $post
		 * @param int $carousel_id
		 */
		public static function wish_list_button( $product, $post, $carousel_id ) {
			$show_wish_list = get_post_meta( $carousel_id, '_product_wishlist', true );

			if ( class_exists( 'YITH_WCWL' ) && Validate::checked( $show_wish_list ) ) {
				echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . $product->get_id() . '"]' );
			}
		}

		/**
		 * Show quick view button on product slider
		 *
		 * @param WC_Product $product
		 * @param WP_Post $post
		 * @param int $carousel_id
		 */
		public static function quick_view_button( $product, $post, $carousel_id ) {
			$_show_btn  = get_post_meta( $carousel_id, '_product_quick_view', true );
			$product_id = $product->get_id();

			if ( $_show_btn == 'on' ) {
				wp_enqueue_script( 'magnific-popup' );

				$ajax_url = ProductUtils::get_product_quick_view_url( $product_id );

				$quick_view_html = '<div style="clear: both;"></div>';
				$quick_view_html .= sprintf(
					'<a class="magnific-popup button quick_view" href="%1$s" data-product-id="%2$s">%3$s</a>',
					$ajax_url,
					$product_id,
					__( 'Quick View', 'carousel-slider' )
				);
				echo apply_filters( 'carousel_slider_product_quick_view', $quick_view_html, $product );
			}
		}

		/**
		 * Display quick view popup content
		 */
		public static function quick_view() {
			if ( ! isset( $_GET['_wpnonce'], $_GET['product_id'] ) ) {
				wp_die();
			}

			if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'carousel_slider_quick_view' ) ) {
				wp_die();
			}

			global $product;
			$product = wc_get_product( intval( $_GET['product_id'] ) );

			?>
			<div id="pmid-<?php echo intval( $_GET['product_id'] ); ?>" class="product carousel-slider__product-modal">

				<div class="images">
					<?php echo get_the_post_thumbnail( $product->get_id(), 'medium_large' ); ?>
					<?php if ( $product->is_on_sale() ) : ?>
						<?php echo apply_filters( 'woocommerce_sale_flash',
							'<span class="onsale">' . __( 'Sale!', 'carousel-slider' ) . '</span>', $product ); ?>
					<?php endif; ?>
				</div>

				<div class="summary entry-summary">

					<h1 class="product_title entry-title">
						<?php echo esc_attr( $product->get_title() ); ?>
					</h1>

					<div class="woocommerce-product-rating">
						<?php
						echo wc_get_rating_html( $product->get_average_rating() );
						?>
					</div>

					<div class="price">
						<?php
						if ( $product->get_price_html() ) {
							echo $product->get_price_html();
						}
						?>
					</div>

					<div class="description">
						<?php
						echo '<div style="clear: both;"></div>';
						echo apply_filters( 'woocommerce_short_description', $product->get_description() );
						?>
					</div>

					<div>
						<?php
						// Show button
						echo '<div style="clear: both;"></div>';
						if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
							woocommerce_template_loop_add_to_cart();
						}
						?>
					</div>
				</div>
			</div>
			<?php
			wp_die();
		}
	}
}

Carousel_Slider_Product::init();
