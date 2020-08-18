<?php

namespace CarouselSlider\StructuredData;

use WC_Product;

defined( 'ABSPATH' ) || die;

class Product {
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

			add_action( 'carousel_slider_product_loop', array( self::$instance, 'generate_product_data' ) );
			// Output structured data.
			add_action( 'wp_footer', array( self::$instance, 'output_structured_data' ), 90 );
		}

		return self::$instance;
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
	 * Outputs structured data.
	 *
	 * Hooked into `wp_footer` action hook.
	 */
	public function output_structured_data() {
		$structured_data = $this->get_structured_product_data();
		if ( $structured_data ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $structured_data ) . '</script>' . "\n";
		}
	}

	/**
	 * Get structured post data
	 *
	 * @return array
	 */
	private function get_structured_product_data() {
		$data = array(
			'@context' => 'http://schema.org/',
			"@graph"   => $this->get_structured_data()
		);

		return $this->get_structured_data() ? $data : array();
	}

	/**
	 * Generates Product structured data.
	 *
	 * Hooked into `carousel_slider_product_loop` action hook.
	 *
	 * @param WC_Product $product Product data (default: null).
	 */
	public function generate_product_data( $product ) {
		if ( ! $product instanceof WC_Product ) {
			return;
		}

		$name      = $product->get_name();
		$permalink = get_permalink( $product->get_id() );

		$markup['@type'] = 'Product';
		$markup['@id']   = $permalink;
		$markup['url']   = $markup['@id'];
		$markup['name']  = $name;

		$this->set_structured_data( apply_filters( 'carousel_slider_structured_data_product', $markup, $product ) );
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

		if ( ! $this->maybe_product_added( $data['@id'] ) ) {
			$this->structured_data[] = $data;
		}

		return true;
	}

	/**
	 * Check if product is already added to list
	 *
	 * @param string $permalink
	 *
	 * @return boolean
	 */
	private function maybe_product_added( $permalink = null ) {
		$product_data = $this->get_structured_data();
		if ( count( $product_data ) ) {
			return in_array( $permalink, wp_list_pluck( $product_data, '@id' ) );
		}

		return false;
	}
}
