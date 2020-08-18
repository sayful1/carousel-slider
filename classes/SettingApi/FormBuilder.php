<?php

namespace CarouselSlider\SettingApi;

use CarouselSlider\Supports\Validate;

defined( 'ABSPATH' ) || die;

class FormBuilder {

	/**
	 * Settings fields
	 *
	 * @param array $fields
	 * @param string $option_name
	 * @param array $values
	 *
	 * @return string
	 */
	public function get_fields_html( array $fields, $option_name, array $values = [] ) {
		$table = "";
		$table .= "<table class='form-table'>";

		foreach ( $fields as $field ) {
			$type  = isset( $field['type'] ) ? $field['type'] : 'text';
			$name  = sprintf( '%s[%s]', $option_name, $field['id'] );
			$value = isset( $values[ $field['id'] ] ) ? $values[ $field['id'] ] : '';

			$table .= "<tr>";
			$table .= sprintf( '<th scope="row"><label for="%1$s">%2$s</label></th>', $field['id'], $field['title'] );
			$table .= "<td>";

			if ( method_exists( $this, $type ) ) {
				$table .= $this->$type( $field, $name, $value );
			} else {
				$table .= $this->text( $field, $name, $value );
			}

			if ( ! empty( $field['description'] ) ) {
				$table .= sprintf( '<p class="description">%s</p>', $field['description'] );
			}
			$table .= "</td>";
			$table .= "</tr>";
		}

		$table .= "</table>";

		return $table;
	}

	/**
	 * text input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function text( $field, $name, $value ) {
		return sprintf( '<input type="text" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
			$field['id'], $name );
	}

	/**
	 * email input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function email( $field, $name, $value ) {
		return sprintf( '<input type="email" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
			$field['id'], $name );
	}

	/**
	 * password input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function password( $field, $name, $value ) {
		return sprintf( '<input type="password" class="regular-text" value="" id="%2$s" name="%3$s">', $value,
			$field['id'], $name );
	}

	/**
	 * number input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function number( $field, $name, $value ) {
		return sprintf( '<input type="number" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
			$field['id'], $name );
	}

	/**
	 * url input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function url( $field, $name, $value ) {
		return sprintf( '<input type="url" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
			$field['id'], $name );
	}

	/**
	 * color input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function color( $field, $name, $value ) {
		$default_color = ( isset( $field['default'] ) ) ? $field['std'] : "";

		return sprintf(
			'<input type="text" class="color-picker" value="%1$s" id="%2$s" name="%3$s" data-alpha="true" data-default-color="%4$s">',
			$value, $field['id'], $name, $default_color );
	}

	/**
	 * date input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function date( $field, $name, $value ) {
		$value = ! empty( $value ) ? date( "Y-m-d", strtotime( $value ) ) : '';

		return sprintf( '<input type="date" class="regular-text" value="%1$s" id="%2$s" name="%3$s">',
			$value, $field['id'], $name );
	}

	/**
	 * date input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function time( $field, $name, $value ) {
		return sprintf( '<input type="time" class="regular-text" value="%1$s" id="%2$s" name="%3$s">',
			$value, $field['id'], $name );
	}

	/**
	 * textarea input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function textarea( $field, $name, $value ) {
		$rows        = ( isset( $field['rows'] ) ) ? $field['rows'] : 5;
		$cols        = ( isset( $field['cols'] ) ) ? $field['cols'] : 40;
		$placeholder = ( isset( $field['placeholder'] ) ) ? sprintf( 'placeholder="%s"',
			esc_attr( $field['placeholder'] ) ) : '';

		return sprintf(
			"<textarea id='%s' name='%s' rows='%s' cols='%s' " . $placeholder . ">" . esc_textarea( $value ) . "</textarea>",
			esc_attr( $field['id'] ), esc_attr( $name ), esc_attr( $rows ), esc_attr( $cols )
		);
	}

	/**
	 * checkbox input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function checkbox( $field, $name, $value ) {
		$true_value  = isset( $field['true-value'] ) ? esc_attr( $field['true-value'] ) : '1';
		$false_value = isset( $field['false-value'] ) ? esc_attr( $field['false-value'] ) : '0';

		$checked = Validate::checked( $value ) ? 'checked' : '';
		$table   = '<input type="hidden" name="' . $name . '" value="' . $false_value . '">';
		$table   .= '<fieldset><legend class="screen-reader-text"><span>' . $field['title'] . '</span></legend>';
		$table   .= '<label for="' . $field['id'] . '">';
		$table   .= '<input type="checkbox" value="' . $true_value . '" id="' . $field['id'] . '" name="' . $name . '" ' . $checked . '>';
		$table   .= $field['title'] . '</label></fieldset>';

		return $table;
	}

	/**
	 * multi checkbox input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param array $value
	 *
	 * @return string
	 */
	public function multi_checkbox( $field, $name, $value ) {
		$table = "<fieldset>";
		$name  = $name . "[]";

		$table .= sprintf( '<input type="hidden" name="%1$s" value="0">', $name );
		foreach ( $field['options'] as $key => $label ) {
			$checked = ( in_array( $key, $value ) ) ? 'checked="checked"' : '';
			$table   .= '<label for="' . $key . '"><input type="checkbox" value="' . $key . '" id="' . $key . '" name="' . $name . '" ' . $checked . '>' . $label . '</label><br>';
		}
		$table .= "</fieldset>";

		return $table;
	}

	/**
	 * radio input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function radio( $field, $name, $value ) {
		$table = '<fieldset><legend class="screen-reader-text"><span>' . $field['title'] . '</span></legend><p>';

		foreach ( $field['options'] as $key => $label ) {

			$checked = ( $value == $key ) ? 'checked="checked"' : '';
			$table   .= '<label><input type="radio" ' . $checked . ' value="' . $key . '" name="' . $name . '">' . $label . '</label><br>';
		}
		$table .= "</p></fieldset>";

		return $table;
	}

	/**
	 * select input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function select( $field, $name, $value ) {
		$table = sprintf( '<select id="%1$s" name="%2$s" class="regular-text">', $field['id'], $name );
		foreach ( $field['options'] as $key => $label ) {
			$selected = ( $value == $key ) ? 'selected="selected"' : '';
			$table    .= '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
		}
		$table .= "</select>";

		return $table;
	}

	/**
	 * Get available image sizes
	 *
	 * @param $field
	 * @param $name
	 * @param $value
	 *
	 * @return string
	 */
	public function image_sizes( $field, $name, $value ) {

		global $_wp_additional_image_sizes;

		$sizes = array();

		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {

				$width  = get_option( "{$_size}_size_w" );
				$height = get_option( "{$_size}_size_h" );
				$crop   = (bool) get_option( "{$_size}_crop" ) ? 'hard' : 'soft';

				$sizes[ $_size ] = "{$_size} - {$width}x{$height} ($crop crop)";

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

				$width  = $_wp_additional_image_sizes[ $_size ]['width'];
				$height = $_wp_additional_image_sizes[ $_size ]['height'];
				$crop   = $_wp_additional_image_sizes[ $_size ]['crop'] ? 'hard' : 'soft';

				$sizes[ $_size ] = "{$_size} - {$width}x{$height} ($crop crop)";
			}
		}

		$sizes = array_merge( $sizes, array( 'full' => 'original uploaded image' ) );

		$table = '<select name="' . $name . '" id="' . $field['id'] . '" class="regular-text select2">';
		foreach ( $sizes as $key => $option ) {
			$selected = ( $value == $key ) ? ' selected="selected"' : '';
			$table    .= '<option value="' . $key . '" ' . $selected . '>' . $option . '</option>';
		}
		$table .= '</select>';

		return $table;
	}

	/**
	 * wp_editor input field
	 *
	 * @param array $field
	 * @param string $name
	 * @param string $value
	 *
	 * @return string
	 */
	public function wp_editor( $field, $name, $value ) {
		ob_start();
		echo "<div class='sp-wp-editor-container'>";
		wp_editor( $value, $field['id'], array(
			'textarea_name' => $name,
			'tinymce'       => false,
			'media_buttons' => false,
			'textarea_rows' => isset( $field['rows'] ) ? $field['rows'] : 6,
			'quicktags'     => array( "buttons" => "strong,em,link,img,ul,li,ol" ),
		) );
		echo "</div>";

		return ob_get_clean();
	}
}
