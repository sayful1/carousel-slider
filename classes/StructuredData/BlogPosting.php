<?php

namespace CarouselSlider\StructuredData;

use WP_Post;

defined( 'ABSPATH' ) || die;

class BlogPosting {

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

			add_action( 'carousel_slider_post_loop', array( self::$instance, 'generate_post_data' ) );
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
		$structured_data = $this->get_structured_post_data();
		if ( $structured_data ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $structured_data ) . '</script>' . "\n";
		}
	}

	/**
	 * Get structured post data
	 *
	 * @return array
	 */
	private function get_structured_post_data() {
		$data = array(
			'@context' => 'http://schema.org/',
			"@graph"   => $this->get_structured_data()
		);

		return $this->get_structured_data() ? $data : array();
	}

	/**
	 * Generates post structured data.
	 *
	 * Hooked into `carousel_slider_post_loop` action hook.
	 *
	 * @param WP_Post $post
	 */
	public function generate_post_data( $post ) {
		if ( ! $post instanceof WP_Post ) {
			return;
		}

		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'normal' );

		$json['@type'] = 'BlogPosting';

		$json['mainEntityOfPage'] = array(
			'@type' => 'webpage',
			'@id'   => get_the_permalink( $post ),
		);


		$json['publisher'] = array(
			'@type' => 'organization',
			'name'  => get_bloginfo( 'name' ),
		);

		$json['author'] = array(
			'@type' => 'person',
			'name'  => get_the_author(),
		);

		if ( is_array( $image ) ) {
			$json['image'] = array(
				'@type'  => 'ImageObject',
				'url'    => $image[0],
				'width'  => $image[1],
				'height' => $image[2],
			);
		}

		$json['datePublished'] = get_post_time( 'c', false, $post );
		$json['dateModified']  = get_the_modified_date( 'c', $post );
		$json['name']          = get_the_title( $post );
		$json['headline']      = $json['name'];
		$json['description']   = get_the_excerpt( $post );


		$this->set_structured_data( apply_filters( 'carousel_slider_structured_data_post', $json, $post ) );
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

		if ( ! $this->maybe_post_added( $data['mainEntityOfPage']['@id'] ) ) {
			$this->structured_data[] = $data;
		}

		return true;
	}


	/**
	 * Check if post is already added to list
	 *
	 * @param string $permalink
	 *
	 * @return boolean
	 */
	private function maybe_post_added( $permalink ) {
		$post_data = $this->get_structured_data();
		if ( count( $post_data ) ) {
			$permalinks = array_map( function ( $data ) {
				return $data['mainEntityOfPage']['@id'];
			}, $post_data );

			return in_array( $permalink, $permalinks );
		}

		return false;
	}
}
