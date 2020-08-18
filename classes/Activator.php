<?php

namespace CarouselSlider;

defined( 'ABSPATH' ) || exit;

class Activator {
	/**
	 * Script that should load upon plugin activation
	 */
	public static function activate() {
		$version = get_option( 'carousel_slider_version' );

		if ( $version == false ) {
			static::update_meta_160();
		}

		// Add plugin version to database
		update_option( 'carousel_slider_version', CAROUSEL_SLIDER_VERSION );
	}

	/**
	 * Update meta for prior to version 1.6.0
	 */
	public static function update_meta_160() {
		$carousels = Utils::get_all_sliders( [ 'post_status' => 'any' ] );

		if ( count( $carousels ) > 0 ) {
			foreach ( $carousels as $carousel ) {

				$id             = $carousel->ID;
				$_items_desktop = get_post_meta( $id, '_items', true );
				$_lazy_load     = get_post_meta( $id, '_lazy_load_image', true );
				$_lazy_load     = $_lazy_load == 'on' ? 'on' : 'off';

				update_post_meta( $id, '_lazy_load_image', $_lazy_load );
				update_post_meta( $id, '_items_desktop', $_items_desktop );
				update_post_meta( $id, '_slide_type', 'image-carousel' );
			}
		}
	}
}
