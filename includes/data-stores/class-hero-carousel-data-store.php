<?php

namespace CarouselSlider\DataStores;

defined( 'ABSPATH' ) || die;

class Hero_Carousel_Data_Store extends Data_Store_Base {
	/**
	 * Meta key to property
	 *
	 * @var string[]
	 */
	protected $meta_key_to_props = [
		'_content_slider'          => 'content',
		'_content_slider_settings' => 'settings',
	];

	/**
	 * Sanitize slider item
	 *
	 * @param array $slide
	 *
	 * @return array
	 */
	public static function sanitize_slider_item( array $slide ) {
		return [
			// Slide Content
			'slide_heading'            => wp_kses_post( static::get_props( $slide, 'slide_heading' ) ),
			'slide_description'        => wp_kses_post( static::get_props( $slide, 'slide_description' ) ),
			// Slide Background
			'img_id'                   => intval( static::get_props( $slide, 'img_id' ) ),
			'img_bg_position'          => sanitize_text_field( static::get_props( $slide, 'img_bg_position' ) ),
			'img_bg_size'              => sanitize_text_field( static::get_props( $slide, 'img_bg_size' ) ),
			'ken_burns_effect'         => sanitize_text_field( static::get_props( $slide, 'ken_burns_effect' ) ),
			'bg_color'                 => static::sanitize_color( static::get_props( $slide, 'bg_color' ) ),
			'bg_overlay'               => static::sanitize_color( static::get_props( $slide, 'bg_overlay' ) ),
			// Slide Style
			'content_alignment'        => sanitize_text_field( static::get_props( $slide, 'content_alignment' ) ),
			'heading_font_size'        => intval( static::get_props( $slide, 'heading_font_size' ) ),
			'heading_gutter'           => sanitize_text_field( static::get_props( $slide, 'heading_gutter' ) ),
			'heading_color'            => static::sanitize_color( static::get_props( $slide, 'heading_color' ) ),
			'description_font_size'    => intval( static::get_props( $slide, 'description_font_size' ) ),
			'description_gutter'       => sanitize_text_field( static::get_props( $slide, 'description_gutter' ) ),
			'description_color'        => static::sanitize_color( static::get_props( $slide, 'description_color' ) ),
			// Slide Link
			'link_type'                => sanitize_text_field( static::get_props( $slide, 'link_type' ) ),
			'slide_link'               => esc_url_raw( static::get_props( $slide, 'slide_link' ) ),
			'link_target'              => sanitize_text_field( static::get_props( $slide, 'link_target' ) ),
			// Slide Button #1
			'button_one_text'          => sanitize_text_field( static::get_props( $slide, 'button_one_text' ) ),
			'button_one_url'           => esc_url_raw( static::get_props( $slide, 'button_one_url' ) ),
			'button_one_target'        => sanitize_text_field( static::get_props( $slide, 'button_one_target' ) ),
			'button_one_type'          => sanitize_text_field( static::get_props( $slide, 'button_one_type' ) ),
			'button_one_size'          => sanitize_text_field( static::get_props( $slide, 'button_one_size' ) ),
			'button_one_border_width'  => sanitize_text_field( static::get_props( $slide, 'button_one_border_width' ) ),
			'button_one_border_radius' => sanitize_text_field( static::get_props( $slide, 'button_one_border_radius' ) ),
			'button_one_bg_color'      => static::sanitize_color( static::get_props( $slide, 'button_one_bg_color' ) ),
			'button_one_color'         => static::sanitize_color( static::get_props( $slide, 'button_one_color' ) ),
			// Slide Button #2
			'button_two_text'          => sanitize_text_field( static::get_props( $slide, 'button_two_text' ) ),
			'button_two_url'           => esc_url_raw( static::get_props( $slide, 'button_two_url' ) ),
			'button_two_target'        => sanitize_text_field( static::get_props( $slide, 'button_two_target' ) ),
			'button_two_type'          => sanitize_text_field( static::get_props( $slide, 'button_two_type' ) ),
			'button_two_size'          => sanitize_text_field( static::get_props( $slide, 'button_two_size' ) ),
			'button_two_border_width'  => sanitize_text_field( static::get_props( $slide, 'button_two_border_width' ) ),
			'button_two_border_radius' => sanitize_text_field( static::get_props( $slide, 'button_two_border_radius' ) ),
			'button_two_bg_color'      => static::sanitize_color( static::get_props( $slide, 'button_two_bg_color' ) ),
			'button_two_color'         => static::sanitize_color( static::get_props( $slide, 'button_two_color' ) ),
		];
	}

	/**
	 * Sanitize slider settings
	 *
	 * @param array $settings
	 *
	 * @return array
	 */
	public static function sanitize_slider_settings( array $settings ) {
		$default_padding = [ 'top' => '', 'right' => '', 'bottom' => '', 'left' => '', ];
		$slide_padding   = wp_parse_args( static::get_props( $settings, 'slide_padding', [] ), $default_padding );

		return [
			'slide_height'      => sanitize_text_field( static::get_props( $settings, 'slide_height' ) ),
			'content_width'     => sanitize_text_field( static::get_props( $settings, 'content_width' ) ),
			'content_animation' => sanitize_text_field( static::get_props( $settings, 'content_animation' ) ),
			'slide_padding'     => map_deep( $slide_padding, 'sanitize_text_field' ),
		];
	}

	/**
	 * Get props from item
	 *
	 * @param array  $data
	 * @param string $key
	 * @param string $default
	 *
	 * @return mixed
	 */
	public static function get_props( array $data, $key, $default = '' ) {
		return isset( $data[ $key ] ) ? $data[ $key ] : $default;
	}
}
