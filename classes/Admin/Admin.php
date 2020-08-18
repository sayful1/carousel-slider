<?php

namespace CarouselSlider\Admin;

use CarouselSlider\SettingApi\DefaultSettingApi;

defined( 'ABSPATH' ) || die;

class Admin {

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			// Add setting page
			add_action( 'init', array( self::$instance, 'settings' ) );

			// Add Documentation page
			add_action( 'admin_menu', array( self::$instance, 'documentation_admin_menu' ) );

			// Change admin footer text
			add_filter( 'admin_footer_text', array( self::$instance, 'admin_footer_text' ) );
		}

		return self::$instance;
	}

	/**
	 * Plugin setting fields
	 */
	public function settings() {
		$settings = new DefaultSettingApi();
		$settings->set_option_name( 'carousel_slider_settings' );
		$settings->add_menu( array(
			'page_title'  => __( 'Carousel Slider Settings', 'carousel-slider' ),
			'menu_title'  => __( 'Settings', 'carousel-slider' ),
			'about_text'  => __( 'Thank you for choosing Carousel Slider. We hope you enjoy it!', 'carousel-slider' ),
			'menu_slug'   => 'settings',
			'parent_slug' => 'edit.php?post_type=carousels',
		) );

		// Add settings page tab
		$settings->set_section( array(
			'id'    => 'general',
			'title' => __( 'General', 'carousel-slider' ),
		) );

		$settings->add_field( array(
			'id'          => 'load_scripts',
			'type'        => 'radio',
			'default'     => 'optimized',
			'title'       => __( 'Style & Scrips', 'carousel-slider' ),
			'description' => __( 'If you choose Optimized, then scrips and styles will be loaded only on page where you are using shortcode. If Optimized is not working for you then choose Always.', 'carousel-slider' ),
			'options'     => array(
				'always'    => __( 'Always', 'carousel-slider' ),
				'optimized' => __( 'Optimized (recommended)', 'carousel-slider' ),
			),
			'tab'         => 'general',
		) );
	}

	/**
	 * Add documentation admin menu page
	 */
	public function documentation_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=carousels',
			'Documentation',
			'Documentation',
			'manage_options',
			'carousel-slider-documentation',
			array( $this, 'documentation_page_callback' )
		);
	}

	/**
	 * Documentation menu page callback
	 */
	public function documentation_page_callback() {
		include_once CAROUSEL_SLIDER_TEMPLATES . '/admin/documentation.php';
	}

	/**
	 * Add custom footer text on plugins page.
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function admin_footer_text( $text ) {
		global $post_type, $hook_suffix;

		$footer_text = sprintf(
			__( 'If you like %1$s Carousel Slider %2$s please leave us a %3$s rating. A huge thanks in advance!', 'carousel-slider' ),
			'<strong>',
			'</strong>',
			'<a href="https://wordpress.org/support/view/plugin-reviews/carousel-slider?filter=5#postform" target="_blank" data-rated="Thanks :)">&starf;&starf;&starf;&starf;&starf;</a>'
		);

		if ( $post_type == 'carousels'
		     || $hook_suffix == 'carousels_page_carousel-slider-documentation'
		     || $hook_suffix == 'carousels_page_settings' ) {
			return $footer_text;
		}

		return $text;
	}
}
