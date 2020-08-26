<?php

namespace CarouselSlider\Carousels\HeroCarousel;

class CarouselItem {
	/**
	 * Get default data
	 *
	 * @return string[]
	 */
	public static function get_default() {
		return [
			// Slide Content
			'slide_heading'            => 'Slide Heading',
			'slide_description'        => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, magnam!',
			// Slide Background
			'img_id'                   => '',
			'img_bg_position'          => 'center center',
			'img_bg_size'              => 'cover',
			'bg_color'                 => 'rgba(0,0,0,0.6)',
			'ken_burns_effect'         => '',
			'bg_overlay'               => '',
			// Slide Style
			'content_alignment'        => 'center',
			'heading_font_size'        => '40',
			'heading_gutter'           => '30px',
			'heading_color'            => '#ffffff',
			'description_font_size'    => '20',
			'description_gutter'       => '30px',
			'description_color'        => '#ffffff',
			// Slide Link
			'link_type'                => 'none',
			'slide_link'               => '',
			'link_target'              => '_self',
			// Slide Button #1
			'button_one_text'          => '',
			'button_one_url'           => '',
			'button_one_target'        => '_self',
			'button_one_type'          => 'stroke',
			'button_one_size'          => 'medium',
			'button_one_border_width'  => '3px',
			'button_one_border_radius' => '0px',
			'button_one_bg_color'      => '#ffffff',
			'button_one_color'         => '#323232',
			// Slide Button #2
			'button_two_text'          => '',
			'button_two_url'           => '',
			'button_two_target'        => '_self',
			'button_two_type'          => 'stroke',
			'button_two_size'          => 'medium',
			'button_two_border_width'  => '3px',
			'button_two_border_radius' => '0px',
			'button_two_bg_color'      => '#ffffff',
			'button_two_color'         => '#323232',
		];
	}
}
