<?php

namespace CarouselSlider\StructuredData;

use WP_Post;

defined( 'ABSPATH' ) || die;

class ImageObject {

	/**
	 * Structured data
	 *
	 * @var array
	 */
	protected $structured_data = [];

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Ensures only one instance of this class is loaded or can be loaded.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;

			add_action( 'carousel_slider_image_gallery_loop', array( self::$instance, 'generate_image_data' ) );
			// Output structured data.
			add_action( 'wp_footer', array( self::$instance, 'output_structured_data' ), 90 );
		}

		return self::$instance;
	}

	/**
	 * Outputs structured data.
	 *
	 * Hooked into `wp_footer` action hook.
	 */
	public function output_structured_data() {
		$structured_data = $this->get_structured_image_data();
		if ( $structured_data ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $structured_data ) . '</script>' . "\n";
		}
	}

	/**
	 * Generates Image structured data.
	 *
	 * Hooked into `carousel_slider_image_gallery_loop` action hook.
	 *
	 * @param WP_Post $post Post data (default: null).
	 */
	public function generate_image_data( $post ) {
		$image                = wp_get_attachment_image_src( $post->ID, 'full' );
		$markup['@type']      = 'ImageObject';
		$markup['contentUrl'] = $image[0];
		$markup['name']       = $post->post_title;

		$this->set_structured_data( apply_filters( 'carousel_slider_structured_data_image', $markup, $post ) );
	}

	/**
	 * Get structure data
	 *
	 * @return array
	 */
	public function get_structured_data() {
		return $this->structured_data;
	}

	/**
	 * Sets data.
	 *
	 * @param array $data Structured data.
	 *
	 * @return bool
	 */
	private function set_structured_data( $data ) {
		if ( ! isset( $data['@type'] ) || ! preg_match( '|^[a-zA-Z]{1,20}$|', $data['@type'] ) ) {
			return false;
		}

		if ( ! $this->maybe_image_added( $data['contentUrl'] ) ) {
			$this->structured_data[] = $data;
		}

		return true;
	}

	/**
	 * Check if image is already added to list
	 *
	 * @param string $contentUrl
	 *
	 * @return boolean
	 */
	public function maybe_image_added( $contentUrl = null ) {
		$structured_data = $this->get_structured_data();
		if ( count( $structured_data ) ) {
			$contentUrls = wp_list_pluck( $structured_data, 'contentUrl' );

			return in_array( $contentUrl, $contentUrls );
		}

		return false;
	}

	/**
	 * Structures and returns image data.
	 * @return array
	 */
	public function get_structured_image_data() {
		$data = array(
			'@context'        => 'http://schema.org/',
			"@type"           => "ImageGallery",
			"associatedMedia" => $this->get_structured_data()
		);

		return $this->get_structured_data() ? $data : array();
	}
}
