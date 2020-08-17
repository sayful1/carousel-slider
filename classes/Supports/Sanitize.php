<?php

namespace CarouselSlider\Supports;

defined( 'ABSPATH' ) || exit;

class Sanitize {
	/**
	 * Sanitize color
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public static function color( $value ) {
		// If the value is empty, then return empty.
		if ( '' === $value ) {
			return '';
		}

		// If transparent, then return 'transparent'.
		if ( is_string( $value ) && 'transparent' === trim( $value ) ) {
			return 'transparent';
		}

		// Trim unneeded whitespace
		$value = str_replace( ' ', '', $value );

		// If this is hex color, validate and return it
		if ( 1 === preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $value ) ) {
			return $value;
		}

		// If this is rgb, validate and return it
		if ( 'rgb(' === substr( $value, 0, 4 ) ) {
			list( $red, $green, $blue ) = sscanf( $value, 'rgb(%d,%d,%d)' );

			if ( ( $red >= 0 && $red <= 255 ) && ( $green >= 0 && $green <= 255 ) && ( $blue >= 0 && $blue <= 255 ) ) {
				return "rgb({$red},{$green},{$blue})";
			}
		}

		// If this is rgba, validate and return it
		if ( 'rgba(' === substr( $value, 0, 5 ) ) {
			list( $red, $green, $blue, $alpha ) = sscanf( $value, 'rgba(%d,%d,%d,%f)' );

			if ( ( $red >= 0 && $red <= 255 ) && ( $green >= 0 && $green <= 255 ) && ( $blue >= 0 && $blue <= 255 ) &&
			     $alpha >= 0 && $alpha <= 1 ) {
				return "rgba({$red},{$green},{$blue},{$alpha})";
			}
		}

		// Not valid color, return empty string
		return '';
	}
}
