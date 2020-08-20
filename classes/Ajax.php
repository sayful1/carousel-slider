<?php

namespace CarouselSlider;

defined( 'ABSPATH' ) || exit;

class Ajax {

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Only one instance of the class can be loaded
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'wp_ajax_carousel_slider_test', [ self::$instance, 'test' ] );
		}

		return self::$instance;
	}

	/**
	 * Test something
	 */
	public function test() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die( 'Only admin can access this page.' );
		}

		/**
		 * Working fine
		 * ================================
		 * recent_products
		 * best_selling_products
		 * featured_products
		 * products_by_categories
		 * products_by_tags
		 * product_categories
		 *
		 * Not Working
		 * ===============================
		 * top_rated_products
		 */
		$data = ProductUtils::products_by_tags( [ 280, 'alias' ] );
		var_dump( $data );
		die();
	}
}
