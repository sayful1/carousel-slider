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

		$data = Utils::get_slider( 207 );
		var_dump( $data );
		die();
	}
}
