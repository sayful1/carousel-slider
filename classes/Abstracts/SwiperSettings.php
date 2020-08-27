<?php

namespace CarouselSlider\Abstracts;

class SwiperSettings {

	/**
	 * @var SliderSettings
	 */
	protected $settings;

	/**
	 * Class constructor.
	 *
	 * @param SliderSettings $settings
	 */
	public function __construct( SliderSettings $settings ) {
		$this->settings = $settings;
	}
}
