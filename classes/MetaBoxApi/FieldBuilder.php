<?php

namespace CarouselSlider\MetaBoxApi;

use CarouselSlider\Utils;

defined( 'ABSPATH' ) || exit;

class FieldBuilder {
	/**
	 * Generate text field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function text( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return '';
		}

		$args['input_attributes']['class'] = 'sp-input-text';
		$attributes                        = static::build_attributes( $args );

		return static::field_before( $args ) . '<input ' . $attributes . '/>' . static::field_after( $args );
	}

	/**
	 * Generate textarea field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function textarea( array $args ) {
		if ( ! isset( $args['id'], $args['name'] ) ) {
			return '';
		}

		$value = static::get_value( $args );

		$args['input_attributes']['class'] = 'sp-input-textarea';
		$args['input_attributes']['cols']  = isset( $args['cols'] ) ? $args['cols'] : 35;
		$args['input_attributes']['rows']  = isset( $args['rows'] ) ? $args['rows'] : 6;

		$html = static::field_before( $args );
		$html .= '<textarea ' . static::build_attributes( $args ) . '>' . esc_textarea( $value ) . '</textarea>';
		$html .= static::field_after( $args );

		return $html;
	}

	/**
	 * Generate color picker field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function color( array $args ) {
		$std_value = isset( $args['std'] ) ? $args['std'] : '';

		$args['type']                                   = 'text';
		$args['input_attributes']['class']              = 'color-picker';
		$args['input_attributes']['data-alpha']         = 'true';
		$args['input_attributes']['data-default-color'] = $std_value;

		return static::text( $args );
	}

	/**
	 * Generate date picker field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function date( array $args ) {
		$args['type']                      = 'date';
		$args['input_attributes']['class'] = 'sp-input-text';

		return static::text( $args );
	}

	/**
	 * Generate number field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function number( array $args ) {
		$args['type']                      = 'number';
		$args['input_attributes']['class'] = 'sp-input-text';

		return static::text( $args );
	}

	/**
	 * Generate checkbox field
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function checkbox( array $args ) {
		$value   = static::get_value( $args );
		$checked = ( $value == 'on' ) ? ' checked' : '';
		$label   = isset( $args['label'] ) ? $args['label'] : '';

		list( $id, $name ) = self::get_name_and_id( $args );

		$html = static::field_before( $args );
		$html .= sprintf( '<input type="hidden" name="%1$s" value="off">', $name );
		$html .= sprintf( '<label for="%2$s"><input type="checkbox" ' . $checked . ' value="on" id="%2$s" name="%1$s">%3$s</label>',
			$name, $id, $label );
		$html .= static::field_after( $args );

		return $html;
	}

	/**
	 * Generate select field
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public static function select( $args ) {
		$value    = static::get_value( $args );
		$multiple = static::is_multiple( $args );

		$args['input_attributes']['class'] = 'select2 sp-input-text';

		$html = static::field_before( $args );
		$html .= '<select ' . static::build_attributes( $args ) . '>';
		foreach ( $args['options'] as $key => $option ) {
			if ( $multiple ) {
				$selected = is_array( $value ) && in_array( $key, $value ) ? ' selected="selected"' : '';
			} else {
				$selected = ( $value == $key ) ? ' selected="selected"' : '';
			}
			$html .= '<option value="' . $key . '" ' . $selected . '>' . $option . '</option>';
		}
		$html .= '</select>';
		$html .= static::field_after( $args );

		return $html;
	}

	/**
	 * Generate posts list dropdown
	 * Also support for any custom post type
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function posts_list( $args ) {
		$posts = get_posts( array(
			'post_type'      => isset( $args['post_type'] ) ? $args['post_type'] : 'post',
			'post_status'    => 'publish',
			'posts_per_page' => - 1
		) );

		foreach ( $posts as $item ) {
			$args['options'][ $item->ID ] = $item->post_title;
		}

		return static::select( $args );
	}

	/**
	 * Generate image sizes dropdown from available image sizes
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function image_sizes( array $args ) {
		$args['options'] = Utils::get_image_sizes();

		return static::select( $args );
	}

	/**
	 * Get post terms drowdown list
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function post_terms( array $args ) {
		$taxonomy = isset( $args['taxonomy'] ) ? $args['taxonomy'] : 'category';
		$terms    = get_terms( array( 'taxonomy' => $taxonomy ) );
		if ( is_wp_error( $terms ) ) {
			return '';
		}

		foreach ( $terms as $term ) {
			$args['options'][ $term->term_id ] = sprintf( '%s (%s)', $term->name, $term->count );
		}

		return static::select( $args );
	}

	/**
	 * Generate image gallery field
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public static function images_gallery( $args ) {
		list( $id, $name ) = self::get_name_and_id( $args );
		$value = static::get_value( $args );
		$value = strip_tags( rtrim( $value, ',' ) );

		$output = '';
		global $post;

		if ( $value ) {
			$thumbs = explode( ',', $value );
			foreach ( $thumbs as $thumb ) {
				$output .= '<li>' . wp_get_attachment_image( $thumb, array( 50, 50 ) ) . '</li>';
			}
		}

		$btn_text        = $value ? 'Edit Gallery' : 'Add Gallery';
		$link_attributes = [
			'id'            => 'carousel_slider_gallery_btn',
			'class'         => 'button',
			'data-id'       => $post->ID,
			'data-ids'      => $value,
			'data-create'   => esc_html__( 'Create Gallery', 'carousel-slider' ),
			'data-edit'     => esc_html__( 'Edit Gallery', 'carousel-slider' ),
			'data-save'     => esc_html__( 'Save Gallery', 'carousel-slider' ),
			'data-progress' => esc_html__( 'Saving...', 'carousel-slider' ),
			'data-insert'   => esc_html__( 'Insert', 'carousel-slider' )
		];

		$html = static::field_before( $args );
		$html .= '<div class="carousel_slider_images">';
		$html .= sprintf( '<input type="hidden" value="%1$s" id="%3$s" name="%2$s">', $value, $name, $id );
		$html .= '<a href="#" ' . static::array_to_attributes( $link_attributes ) . '>' . esc_html( $btn_text ) . '</a>';
		$html .= '<ul class="carousel_slider_gallery_list">' . $output . '</ul>';
		$html .= '</div>';
		$html .= static::field_after( $args );

		return $html;
	}

	/**
	 * Generate image gallery list from images URL
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function images_url( array $args ) {
		$value    = static::get_value( $args );
		$btn_text = $value ? __( 'Edit URLs', 'carousel-slider' ) : __( 'Add URLs', 'carousel-slider' );

		$html = static::field_before( $args );
		$html .= '<a id="_images_urls_btn" class="button" href="#">' . esc_html( $btn_text ) . '</a>';
		$html .= '<ul class="carousel_slider_url_images_list">';
		if ( is_array( $value ) && count( $value ) > 0 ) {
			foreach ( $value as $image ) {
				$html .= '<li><img src="' . $image['url'] . '" alt="' . $image['alt'] . '" width="75" height="75"></li>';
			}
		}
		$html .= '</ul>';
		$html .= static::field_after( $args );

		return $html;
	}

	/**
	 * Generate field before template
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	private static function field_before( array $args ) {
		list( $id ) = self::get_name_and_id( $args );

		$_normal = sprintf( '<div class="sp-input-group" id="field-%s">', $id );
		$_normal .= sprintf( '<div class="sp-input-label">' );
		$_normal .= sprintf( '<label for="%1$s">%2$s</label>', $id, $args['name'] );
		if ( ! empty( $args['desc'] ) ) {
			$_normal .= sprintf( '<p class="sp-input-desc">%s</p>', $args['desc'] );
		}
		$_normal .= '</div>';
		$_normal .= sprintf( '<div class="sp-input-field">' );

		if ( isset( $args['context'] ) && 'side' == $args['context'] ) {
			$_side = '<p id="field-' . $id . '">';
			$_side .= '<label for="' . $id . '"><strong>' . $args['name'] . '</strong></label>';

			return $_side;
		}

		return $_normal;
	}

	/**
	 * Generate field after template
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	private static function field_after( array $args = array() ) {
		if ( isset( $args['context'] ) && 'side' == $args['context'] ) {
			$_side = '';
			if ( ! empty( $args['desc'] ) ) {
				$_side .= '<span class="cs-tooltip" title="' . esc_attr( $args['desc'] ) . '"></span>';
			}
			$_side .= '</p>';

			return $_side;
		}

		return '</div></div>';
	}

	/**
	 * Get input attribute name
	 *
	 * @param array $args
	 *
	 * @return mixed|string
	 */
	private static function get_name_and_id( array $args ) {
		$group = isset( $args['group'] ) ? $args['group'] : 'carousel_slider';
		$index = isset( $args['index'] ) ? $args['index'] : false;
		$id    = $args['id'];
		$name  = $id;

		if ( $group ) {
			if ( false !== $index ) {
				$name = $group . '[' . $index . ']' . '[' . $name . ']';
				$id   = $group . '_' . $index . '_' . $id;
			} else {
				$name = $group . '[' . $name . ']';
				$id   = $group . '_' . $id;
			}
		}

		if ( self::is_multiple( $args ) ) {
			$name = $name . '[]';
		}

		return array( $id, $name );
	}

	/**
	 * Get meta value
	 *
	 * @param array $args
	 *
	 * @return mixed
	 */
	private static function get_value( array $args ) {
		global $post;

		$default = isset( $args['default'] ) ? $args['default'] : '';
		$meta    = get_post_meta( $post->ID, $args['id'], true );
		$value   = ! empty( $meta ) ? $meta : $default;

		if ( isset( $args['meta_key'] ) ) {
			$meta  = get_post_meta( $post->ID, $args['meta_key'], true );
			$value = ! empty( $meta[ $args['id'] ] ) ? $meta[ $args['id'] ] : $default;

			if ( isset( $args['index'] ) ) {
				$value = ! empty( $meta[ $args['index'] ][ $args['id'] ] ) ? $meta[ $args['index'] ][ $args['id'] ] : $default;
			}
		}

		if ( $value == 'zero' ) {
			$value = 0;
		}

		return $value;
	}

	/**
	 * Generate input attribute
	 *
	 * @param array $args
	 *
	 * @return array|string
	 */
	private static function build_attributes( array $args ) {
		$input_type       = isset( $args['type'] ) ? $args['type'] : 'text';
		$input_attributes = isset( $args['input_attributes'] ) ? $args['input_attributes'] : array();
		list( $id, $name ) = self::get_name_and_id( $args );

		$attributes = array( 'id' => $id, 'name' => $name, );

		if ( ! in_array( $input_type, array( 'textarea', 'select' ) ) ) {
			$attributes['type'] = $input_type;
		}

		if ( ! in_array( $input_type, array( 'textarea', 'file', 'password', 'select' ) ) ) {
			$attributes['value'] = self::get_value( $args );
		}

		if ( self::is_multiple( $args ) ) {
			$attributes['multiple'] = true;
		}

		if ( 'hidden' === $input_type ) {
			$attributes['spellcheck']   = false;
			$attributes['tabindex']     = '-1';
			$attributes['autocomplete'] = 'off';
		}

		if ( ! in_array( $input_type, array( 'hidden', 'image', 'submit', 'reset', 'button' ) ) ) {
			$attributes['required'] = self::is_required( $args );
		}

		foreach ( $input_attributes as $attribute => $value ) {
			$attributes[ $attribute ] = $value;
		}

		return self::array_to_attributes( $attributes );
	}

	/**
	 * Check if input support multiple value
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	private static function is_multiple( $args ) {
		if ( isset( $args['multiple'] ) && $args['multiple'] ) {
			return true;
		}

		if ( isset( $args['input_attributes']['multiple'] ) && $args['input_attributes']['multiple'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if input is required
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	private static function is_required( array $args ) {
		if ( isset( $args['required'] ) && $args['required'] ) {
			return true;
		}

		if ( isset( $args['input_attributes']['required'] ) && $args['input_attributes']['required'] ) {
			return true;
		}

		return false;
	}

	/**
	 * Convert array to HTML attribute
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	private static function array_to_attributes( array $data ) {
		return Utils::array_to_attributes( $data );
	}
}
