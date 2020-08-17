<?php

namespace CarouselSlider\Abstracts;

use ArrayAccess;
use JsonSerializable;

defined( 'ABSPATH' ) || exit;

/**
 * Class Data
 * @package CarouselSlider\Abstracts
 */
class Data implements ArrayAccess, JsonSerializable {

	/**
	 * Object data
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * String representation of the class
	 *
	 * @return string
	 */
	public function __toString() {
		return json_encode( $this->to_array() );
	}

	/**
	 * Get collection item for key
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		return $this->get_prop( $name );
	}

	/**
	 * Does this collection have a given key?
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function __isset( $name ) {
		return $this->has_prop( $name );
	}

	/**
	 * Array representation of the class
	 *
	 * @return array
	 */
	public function to_array() {
		return $this->data;
	}

	/**
	 * Does this collection have a given key?
	 *
	 * @param string $key The data key
	 *
	 * @return bool
	 */
	public function has_prop( $key ) {
		return isset( $this->data[ $key ] );
	}

	/**
	 * Set collection item
	 *
	 * @param string $key The data key
	 * @param mixed $value The data value
	 */
	public function set_prop( $key, $value ) {
		$this->data[ $key ] = $value;
	}

	/**
	 * Get collection item for key
	 *
	 * @param string $key The data key
	 * @param mixed $default The default value to return if data key does not exist
	 *
	 * @return mixed The key's value, or the default value
	 */
	public function get_prop( $key, $default = '' ) {
		if ( $this->has_prop( $key ) ) {
			$value = $this->data[ $key ];
			if ( is_numeric( $value ) ) {
				return is_float( $value ) ? (float) $value : (int) $value;
			}

			return $value;
		}

		return $default;
	}

	/**
	 * Remove item from collection
	 *
	 * @param string $key The data key
	 */
	public function remove_prop( $key ) {
		if ( $this->has_prop( $key ) ) {
			unset( $this->data[ $key ] );
		}
	}

	/**
	 * Whether a offset exists
	 *
	 * @param mixed $offset An offset to check for.
	 *
	 * @return boolean true on success or false on failure.
	 */
	public function offsetExists( $offset ) {
		return $this->has_prop( $offset );
	}

	/**
	 * Offset to retrieve
	 *
	 * @param mixed $offset The offset to retrieve.
	 *
	 * @return mixed Can return all value types.
	 */
	public function offsetGet( $offset ) {
		return $this->get_prop( $offset );
	}

	/**
	 * Offset to set
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value The value to set.
	 *
	 * @return void
	 */
	public function offsetSet( $offset, $value ) {
		$this->set_prop( $offset, $value );
	}

	/**
	 * Offset to unset
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 */
	public function offsetUnset( $offset ) {
		$this->remove_prop( $offset );
	}

	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @return mixed data which can be serialized by json_encode
	 * which is a value of any type other than a resource.
	 */
	public function jsonSerialize() {
		return $this->to_array();
	}
}
