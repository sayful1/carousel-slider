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
}
