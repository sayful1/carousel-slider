<?php

namespace CarouselSlider\Supports\FormFields;

/**
 * HTML class
 */
class Html extends BaseField {

	/**
	 * Render field
	 *
	 * @return string
	 */
	public function render(): string {
		$html = $this->get_setting( 'html', '' );
		if ( ! is_string( $html ) ) {
			return '';
		}

		return $html;
	}
}
