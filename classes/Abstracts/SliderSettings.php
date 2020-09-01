<?php

namespace CarouselSlider\Abstracts;

defined( 'ABSPATH' ) || exit;

class SliderSettings extends Data {

	/**
	 * Global settings
	 *
	 * @var array
	 */
	protected static $global_settings = [];

	/**
	 * Check if global settings read
	 *
	 * @var bool
	 */
	protected static $global_settings_read = false;

	/**
	 * SliderSettings constructor.
	 *
	 * @param array $data
	 */
	public function __construct( array $data = [] ) {
		$this->data = $data;
	}

	/**
	 * Get global settings that applied for all types of sliders
	 */
	public function get_global_settings() {
		if ( static::$global_settings_read ) {
			return static::$global_settings;
		}

		$default = [
			'load_scripts' => 'optimized',
		];

		static::$global_settings      = wp_parse_args( get_option( 'carousel_slider_settings', [] ), $default );
		static::$global_settings_read = true;

		return static::$global_settings;
	}

	/**
	 * Get slider settings
	 */
	public function get_slider_settings() {
		return [];
	}

	/**
	 * Get style settings
	 */
	public function get_style_settings() {
		return [];
	}


	/**
	 * Get CSS variable
	 *
	 * @param bool $as_string
	 *
	 * @return array|string
	 */
	public function get_css_var( $as_string = false ) {
		$vars = [
			'--arrow-size'       => $this->get_prop( 'arrow_size', 48 ) . 'px',
			'--dot-size'         => $this->get_prop( 'dot_size', 10 ) . 'px',
			'--nav-color'        => $this->get_prop( 'nav_color' ),
			'--nav-active-color' => $this->get_prop( 'nav_active_color' ),
		];

		if ( $as_string ) {
			return static::css_var_to_string( $vars );
		}

		return $vars;
	}

	/**
	 * Convert CSS var to string
	 *
	 * @param array $vars
	 *
	 * @return string
	 */
	public static function css_var_to_string( array $vars ) {
		$styles = '';
		foreach ( $vars as $prop => $value ) {
			if ( empty( $value ) || empty( $prop ) ) {
				continue;
			}
			$styles .= sprintf( "%s:%s;", esc_attr( $prop ), esc_attr( $value ) );
		}

		return $styles;
	}
}
