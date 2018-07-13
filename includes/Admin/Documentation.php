<?php

namespace CarouselSlider\Admin;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Documentation {

	protected static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return Documentation
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu() {
		add_submenu_page(
			'edit.php?post_type=carousels',
			'Documentation',
			'Documentation',
			'manage_options',
			'carousel-slider-documentation',
			array( $this, 'submenu_page_callback' )
		);
	}

	public function submenu_page_callback() {
		include_once CAROUSEL_SLIDER_TEMPLATES . '/admin/documentation.php';
	}
}

