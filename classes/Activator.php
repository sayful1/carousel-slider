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

		if ( version_compare( $version, '2.0', '<' ) ) {
			static::update_meta_200();
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

	/**
	 * Upgrade to 2.0
	 */
	private static function update_meta_200() {
		$carousels = Utils::get_all_sliders( [ 'post_status' => 'any' ] );
		if ( count( $carousels ) ) {
			foreach ( $carousels as $carousel ) {
				$loop = get_post_meta( $carousel->ID, '_inifnity_loop', true );
				if ( in_array( $loop, [ 'on', 'off' ] ) ) {
					update_post_meta( $carousel->ID, '_infinity_loop', $loop );
					delete_post_meta( $carousel->ID, '_inifnity_loop' );
				}
			}
		}
	}
}
