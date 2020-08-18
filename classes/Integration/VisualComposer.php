<?php

namespace CarouselSlider\Integration;

use CarouselSlider\Utils;

defined( 'ABSPATH' ) || exit;

class VisualComposer {

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

			add_action( 'init', [ self::$instance, 'integrate_with_vc' ] );
		}

		return self::$instance;
	}

	/**
	 * Integrate with visual composer
	 */
	public function integrate_with_vc() {
		// Check if Visual Composer is installed
		if ( ! function_exists( 'vc_map' ) ) {
			return;
		}

		vc_map( array(
			"name"        => __( "Carousel Slider", 'carousel-slider' ),
			"description" => __( "Place Carousel Slider.", 'carousel-slider' ),
			"base"        => "carousel_slide",
			"controls"    => "full",
			"icon"        => CAROUSEL_SLIDER_ASSETS . '/img/logo.svg',
			"category"    => __( 'Content', 'carousel-slider' ),
			"params"      => array(
				array(
					"type"       => "dropdown",
					"holder"     => "div",
					"class"      => "carousel-slider-id",
					"param_name" => "id",
					"value"      => $this->carousels_list(),
					"heading"    => __( "Choose Carousel Slide", 'carousel-slider' ),
				),
			),
		) );
	}

	/**
	 * Generate array for carousel slider
	 *
	 * @return array
	 */
	private function carousels_list() {
		$carousels = Utils::get_all_sliders();

		if ( count( $carousels ) < 1 ) {
			return array();
		}

		$result = array();

		foreach ( $carousels as $carousel ) {
			$result[ esc_html( $carousel->post_title ) ] = $carousel->ID;
		}

		return $result;
	}
}
