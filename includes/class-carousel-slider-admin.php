<?php

use CarouselSlider\DataStores\HeroCarouselDataStore;
use CarouselSlider\DataStores\ImageCarouselDataStore;

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Carousel_Slider_Admin' ) ) {

	class Carousel_Slider_Admin {

		/**
		 * @var Carousel_Slider_Form
		 */
		private $form;

		/**
		 * The instance of the class
		 *
		 * @var self
		 */
		protected static $instance = null;

		/**
		 * Ensures only one instance of this class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider_Admin
		 */
		public static function init() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				self::$instance->form = new Carousel_Slider_Form();

				add_action( 'add_meta_boxes', array( self::$instance, 'add_meta_boxes' ) );
				add_action( 'save_post', array( self::$instance, 'save_meta_box' ) );
			}

			return self::$instance;
		}

		/**
		 * Add carousel slider meta box
		 */
		public function add_meta_boxes() {
			add_meta_box(
				"carousel-slider-meta-boxes",
				__( "Carousel Slider", 'carousel-slider' ),
				array( $this, 'carousel_slider_meta_boxes' ),
				"carousels",
				"normal",
				"high"
			);
		}

		/**
		 * Load meta box content
		 *
		 * @param WP_Post $post
		 */
		public function carousel_slider_meta_boxes( $post ) {
			wp_nonce_field( 'carousel_slider_nonce', '_carousel_slider_nonce' );

			$slide_type = get_post_meta( $post->ID, '_slide_type', true );
			$slide_type = in_array( $slide_type, carousel_slider_slide_type() ) ? $slide_type : 'image-carousel';

			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/types.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-media.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-url.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/post-carousel.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/product-carousel.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/video-carousel.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/hero-banner-slider.php';
			require_once CAROUSEL_SLIDER_TEMPLATES . '/admin/images-settings.php';
		}

		/**
		 * Save custom meta box
		 *
		 * @param int $post_id The post ID
		 */
		public function save_meta_box( $post_id ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			// Check if nonce is set.
			if ( ! isset( $_POST['_carousel_slider_nonce'], $_POST['carousel_slider'] ) ) {
				return;
			}
			// Check if nonce is valid.
			if ( ! wp_verify_nonce( $_POST['_carousel_slider_nonce'], 'carousel_slider_nonce' ) ) {
				return;
			}
			// Check if user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			if ( isset( $_POST['carousel_slider_content'] ) ) {
				$this->update_content_slider( $post_id );
			}

			if ( isset( $_POST['content_settings'] ) ) {
				$this->update_content_settings( $post_id );
			}

			foreach ( $_POST['carousel_slider'] as $key => $val ) {
				if ( is_array( $val ) ) {
					$val = implode( ',', $val );
				}

				if ( $key == '_margin_right' && $val == 0 ) {
					$val = 'zero';
				}
				update_post_meta( $post_id, $key, sanitize_text_field( $val ) );
			}

			if ( ! isset( $_POST['carousel_slider']['_post_categories'] ) ) {
				update_post_meta( $post_id, '_post_categories', '' );
			}

			if ( ! isset( $_POST['carousel_slider']['_post_tags'] ) ) {
				update_post_meta( $post_id, '_post_tags', '' );
			}

			if ( ! isset( $_POST['carousel_slider']['_post_in'] ) ) {
				update_post_meta( $post_id, '_post_in', '' );
			}

			if ( isset( $_POST['_images_urls'] ) ) {
				$this->save_images_urls( $post_id );
			}
		}

		/**
		 * Save images urls
		 *
		 * @param integer $post_id
		 *
		 * @return void
		 */
		private function save_images_urls( $post_id ) {
			$urls  = isset( $_POST['_images_urls'] ) ? $_POST['_images_urls'] : [];
			$count = isset( $urls['url'] ) && is_array( $urls['url'] ) ? count( $urls['url'] ) : 0;
			if ( $count < 1 ) {
				return;
			}
			$data = [];
			foreach ( range( 1, $count ) as $i => $item ) {
				$data[] = [
					'url'      => isset( $urls['url'][ $i ] ) ? $urls['url'][ $i ] : '',
					'title'    => isset( $urls['title'][ $i ] ) ? $urls['title'][ $i ] : '',
					'caption'  => isset( $urls['caption'][ $i ] ) ? $urls['caption'][ $i ] : '',
					'alt'      => isset( $urls['alt'][ $i ] ) ? $urls['alt'][ $i ] : '',
					'link_url' => isset( $urls['link_url'][ $i ] ) ? $urls['link_url'][ $i ] : '',
				];
			}

			update_post_meta( $post_id, '_images_urls', ImageCarouselDataStore::sanitize_external_urls( $data ) );
		}

		/**
		 * Update content slider
		 *
		 * @param int $post_id
		 */
		private function update_content_slider( $post_id ) {
			$content = is_array( $_POST['carousel_slider_content'] ) ? $_POST['carousel_slider_content'] : [];
			$data    = [];
			foreach ( $content as $item ) {
				$data[] = HeroCarouselDataStore::sanitize_slider_item( $item );
			}

			update_post_meta( $post_id, '_content_slider', $data );
		}

		/**
		 * Update hero carousel settings
		 *
		 * @param int $post_id post id
		 */
		private function update_content_settings( $post_id ) {
			$setting   = is_array( $_POST['content_settings'] ) ? $_POST['content_settings'] : [];
			$_settings = HeroCarouselDataStore::sanitize_slider_settings( $setting );
			update_post_meta( $post_id, '_content_slider_settings', $_settings );
		}
	}
}

Carousel_Slider_Admin::init();
