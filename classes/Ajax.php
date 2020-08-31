<?php

namespace CarouselSlider;

use CarouselSlider\Carousels\HeroCarousel\CarouselItem;

defined( 'ABSPATH' ) || exit;

class Ajax {

	/**
	 * The instance of the class
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Only one instance of the class can be loaded
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			add_action( 'wp_ajax_carousel_slider_test', [ self::$instance, 'test' ] );
			add_action( 'wp_ajax_add_content_slide', array( self::$instance, 'add_content_slide' ) );
		}

		return self::$instance;
	}

	/**
	 * Test something
	 */
	public function test() {
		if ( ! current_user_can( 'manage_options' ) ) {
			die( 'Only admin can access this page.' );
		}

		$data = Utils::get_slider( 208 );
		var_dump( $data );
		die();
	}

	/**
	 * Add content carousel item
	 */
	public function add_content_slide() {
		if ( ! isset( $_POST['post_id'] ) ) {
			wp_send_json( __( 'Required attribute is not set properly.', 'carousel-slider' ), 422 );
		}

		$post_id          = absint( $_POST['post_id'] );
		$task             = isset( $_POST['task'] ) ? esc_attr( $_POST['task'] ) : 'add-slide';
		$current_position = isset( $_POST['slide_pos'] ) ? absint( $_POST['slide_pos'] ) : null;

		$items = get_post_meta( $post_id, '_content_slider', true );
		$items = is_array( $items ) ? $items : [];

		$last_index = count( $items ) - 1;

		if ( $task == 'add-slide' ) {
			$items[] = CarouselItem::get_default();
		}
		if ( $task == 'delete-slide' && ! is_null( $current_position ) ) {
			array_splice( $items, $current_position, 1 );
		}
		if ( $task == 'move-slide-top' && ! is_null( $current_position ) ) {
			if ( $current_position > 0 ) {
				$items = $this->move_array_element( $items, $current_position, 0 );
			}
		}
		if ( $task == 'move-slide-up' && ! is_null( $current_position ) ) {
			if ( $current_position > 0 ) {
				$items = $this->move_array_element( $items, $current_position, ( $current_position - 1 ) );
			}
		}
		if ( $task == 'move-slide-down' && ! is_null( $current_position ) ) {
			if ( $current_position < $last_index ) {
				$items = $this->move_array_element( $items, $current_position, ( $current_position + 1 ) );
			}
		}
		if ( $task == 'move-slide-bottom' && ! is_null( $current_position ) ) {
			if ( $current_position < $last_index ) {
				$items = $this->move_array_element( $items, $current_position, $last_index );
			}
		}

		update_post_meta( $post_id, '_content_slider', $items );
		wp_send_json( $items, 200 );
	}

	/**
	 * Move array element position
	 *
	 * @param array $array
	 * @param int $current_index
	 * @param int $new_index
	 *
	 * @return mixed
	 */
	private function move_array_element( array $array, $current_index, $new_index ) {
		$output = array_splice( $array, $current_index, 1 );
		array_splice( $array, $new_index, 0, $output );

		return $array;
	}
}
