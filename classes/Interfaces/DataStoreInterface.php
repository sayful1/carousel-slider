<?php

namespace CarouselSlider\Interfaces;

use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Interface DataStoreInterface
 * @package CarouselSlider\Interfaces
 */
interface DataStoreInterface {

	/**
	 * Method to create a new record
	 *
	 * @param array $data
	 *
	 * @return mixed
	 */
	public function create( array $data );

	/**
	 * Method to read a record.
	 *
	 * @param int|WP_Post $post
	 *
	 * @return mixed
	 */
	public function read( $post );

	/**
	 * Updates a record in the database.
	 *
	 * @param array $data
	 *
	 * @return mixed
	 */
	public function update( array $data );

	/**
	 * Deletes a record from the database.
	 *
	 * @param mixed $data
	 *
	 * @return bool
	 */
	public function delete( $data );
}
