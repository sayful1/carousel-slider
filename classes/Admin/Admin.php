<?php

namespace CarouselSlider\Admin;

use CarouselSlider\SettingApi\DefaultSettingApi;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Utils;
use WP_Post;

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

			// Register post type
			add_action( 'init', [ self::$instance, 'register_post_type' ] );
			add_filter( 'manage_edit-' . Utils::POST_TYPE . '_columns', [ self::$instance, 'columns_head' ] );
			add_filter( 'manage_' . Utils::POST_TYPE . '_posts_custom_column',
				[ self::$instance, 'columns_content' ], 10, 2 );
			// Remove view and Quick Edit from Carousels
			add_filter( 'post_row_actions', [ self::$instance, 'post_row_actions' ], 10, 2 );

			// Add custom link to media gallery
			add_filter( "attachment_fields_to_edit", [ self::$instance, "attachment_fields_to_edit" ], 10, 2 );
			add_filter( "attachment_fields_to_save", [ self::$instance, "attachment_fields_to_save" ], 10, 2 );

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
	 * Register post type
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => _x( 'Slides', 'Post Type General Name', 'carousel-slider' ),
			'singular_name'      => _x( 'Slide', 'Post Type Singular Name', 'carousel-slider' ),
			'menu_name'          => __( 'Carousel Slider', 'carousel-slider' ),
			'parent_item_colon'  => __( 'Parent Slide:', 'carousel-slider' ),
			'all_items'          => __( 'All Slides', 'carousel-slider' ),
			'view_item'          => __( 'View Slide', 'carousel-slider' ),
			'add_new_item'       => __( 'Add New Slide', 'carousel-slider' ),
			'add_new'            => __( 'Add New', 'carousel-slider' ),
			'edit_item'          => __( 'Edit Slide', 'carousel-slider' ),
			'update_item'        => __( 'Update Slide', 'carousel-slider' ),
			'search_items'       => __( 'Search Slide', 'carousel-slider' ),
			'not_found'          => __( 'Not found', 'carousel-slider' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'carousel-slider' ),
		);
		$args   = array(
			'label'               => __( 'Slide', 'carousel-slider' ),
			'description'         => __( 'The easiest way to create carousel slide', 'carousel-slider' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5.55525,
			'menu_icon'           => 'dashicons-slides',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => false,
			'capability_type'     => 'post',
		);

		register_post_type( Utils::POST_TYPE, $args );
	}

	/**
	 * Customize Carousel slider list table head
	 *
	 * @return array A list of column headers.
	 */
	public function columns_head() {
		return array(
			'cb'         => '<input type="checkbox">',
			'title'      => __( 'Carousel Slide Title', 'carousel-slider' ),
			'usage'      => __( 'Shortcode', 'carousel-slider' ),
			'slide_type' => __( 'Slide Type', 'carousel-slider' )
		);
	}

	/**
	 * Generate carousel slider list table content for each custom column
	 *
	 * @param string $column_name The name of the column to display.
	 * @param int $post_id The current post ID.
	 */
	public function columns_content( $column_name, $post_id ) {
		if ( $column_name == 'slide_type' ) {
			$slide_types = Utils::slide_type();
			$slide_type  = get_post_meta( $post_id, '_slide_type', true );
			echo isset( $slide_types[ $slide_type ] ) ? esc_attr( $slide_types[ $slide_type ] ) : '';
		}
		if ( $column_name == 'usage' ) {
			$attributes = [
				'type'  => 'text',
				'id'    => sprintf( "carousel_slider_usage_%s", $post_id ),
				'class' => 'cs-copy-top-clipboard',
				'value' => sprintf( "[carousel_slide id='%s']", $post_id ),
			];

			echo sprintf( '<label class="screen-reader-text" for="carousel_slider_usage_%s">%s</label>',
				$post_id, __( 'Copy shortcode', 'carousel-slider' ) );
			echo '<input ' . Utils::array_to_attributes( $attributes ) . '/>';
		}
	}

	/**
	 * Hide view and quick edit from carousel slider admin
	 *
	 * @param array $actions
	 * @param WP_Post $post
	 *
	 * @return array
	 */
	public function post_row_actions( $actions, $post ) {
		if ( $post->post_type != Utils::POST_TYPE ) {
			return $actions;
		}

		$view_url        = Utils::get_slider_preview_url( $post );
		$actions['view'] = '<a href="' . $view_url . '" target="_blank">' . esc_html__( 'Preview', 'carousel-slider' ) . '</a>';

		unset( $actions['inline hide-if-no-js'] );

		return $actions;
	}


	/**
	 * Adding our custom fields to the $form_fields array
	 *
	 * @param array $form_fields
	 * @param WP_Post $post
	 *
	 * @return array
	 */
	public function attachment_fields_to_edit( $form_fields, $post ) {
		$field = [
			'label'      => __( "Link to URL", "carousel-slider" ),
			'input'      => 'textarea',
			'value'      => get_post_meta( $post->ID, "_carousel_slider_link_url", true ),
			'extra_rows' => [
				'carouselSliderInfo' => __( '"Link to URL" only works on Carousel Slider for linking image to a custom url.',
					'carousel-slider' ),
			],
		];

		$form_fields["carousel_slider_link_url"] = $field;

		return $form_fields;
	}

	/**
	 * Save custom field value
	 *
	 * @param array $post
	 * @param array $attachment
	 *
	 * @return object|array
	 */
	public function attachment_fields_to_save( $post, $attachment ) {
		$slider_link_url = isset( $attachment['carousel_slider_link_url'] ) ? $attachment['carousel_slider_link_url'] : null;

		if ( Validate::url( $slider_link_url ) ) {
			update_post_meta( $post['ID'], '_carousel_slider_link_url', esc_url_raw( $slider_link_url ) );
		} else {
			delete_post_meta( $post['ID'], '_carousel_slider_link_url' );
		}

		return $post;
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
