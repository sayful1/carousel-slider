<?php

use CarouselSlider\Carousels\PostCarousel\PostUtils;
use CarouselSlider\Carousels\ProductCarousel\ProductUtils;
use CarouselSlider\Supports\Sanitize;
use CarouselSlider\Supports\Validate;
use CarouselSlider\Utils;

defined( 'ABSPATH' ) || die;

if ( ! function_exists( 'carousel_slider_is_url' ) ) {
	/**
	 * Check if url is valid as per RFC 2396 Generic Syntax
	 *
	 * @param string $url
	 *
	 * @return boolean
	 */
	function carousel_slider_is_url( $url ) {
		_deprecated_function( __FUNCTION__, '2.0.0', Validate::class . '::url()' );

		return Validate::url( $url );
	}
}

if ( ! function_exists( 'carousel_slider_sanitize_color' ) ) {
	/**
	 * Sanitizes a Hex, RGB or RGBA color
	 *
	 * @param $color
	 *
	 * @return mixed|string
	 */
	function carousel_slider_sanitize_color( $color ) {
		_deprecated_function( __FUNCTION__, '2.0.0', Sanitize::class . '::color()' );

		return Sanitize::color( $color );
	}
}

if ( ! function_exists( 'carousel_slider_get_meta' ) ) {
	/**
	 * Get post meta by id and key
	 *
	 * @param $id
	 * @param $key
	 * @param null $default
	 *
	 * @return string
	 */
	function carousel_slider_get_meta( $id, $key, $default = null ) {
		_deprecated_function( __FUNCTION__, '2.0.0' );

		$data = get_post_meta( $id, $key, true );

		return ! empty( $data ) ? $data : $default;
	}
}

if ( ! function_exists( 'carousel_slider_array_to_attribute' ) ) {
	/**
	 * Convert array to html data attribute
	 *
	 * @param $array
	 *
	 * @return array|string
	 */
	function carousel_slider_array_to_attribute( $array ) {
		_deprecated_function( __FUNCTION__, '2.0.0', Utils::class . '::array_to_attributes()' );

		return Utils::array_to_attributes( $array );
	}
}

if ( ! function_exists( 'carousel_slider_is_woocommerce_active' ) ) {
	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 */
	function carousel_slider_is_woocommerce_active() {
		_deprecated_function( __FUNCTION__, '2.0.0', Utils::class . '::is_woocommerce_active()' );

		return Utils::is_woocommerce_active();
	}
}

if ( ! function_exists( 'carousel_slider_posts' ) ) {
	/**
	 * Get posts by carousel slider ID
	 *
	 * @param $id
	 *
	 * @return array
	 */
	function carousel_slider_posts( $id ) {
		_deprecated_function( __FUNCTION__, '2.0.0', PostUtils::class . '::get_posts()' );

		return PostUtils::get_posts( $id );
	}
}

if ( ! function_exists( 'carousel_slider_products' ) ) {
	/**
	 * Get products by carousel slider ID
	 *
	 * @param int $id
	 *
	 * @return array
	 */
	function carousel_slider_products( $id ) {
		_deprecated_function( __FUNCTION__, '2.0.0', ProductUtils::class . '::get_products()' );

		return ProductUtils::get_products( $id );
	}
}

if ( ! function_exists( 'carousel_slider_slide_type' ) ) {
	/**
	 * Get carousel slider available slide type
	 *
	 * @param bool $key_only
	 *
	 * @return array
	 */
	function carousel_slider_slide_type( $key_only = true ) {
		_deprecated_function( __FUNCTION__, '2.0.0', Utils::class . '::slide_type()' );

		$types = Utils::slide_type();

		if ( $key_only ) {
			return array_keys( $types );
		}

		return $types;
	}
}

if ( ! function_exists( 'carousel_slider_background_position' ) ) {
	/**
	 * @param bool $key_only
	 *
	 * @return array
	 */
	function carousel_slider_background_position( $key_only = false ) {
		_deprecated_function( __FUNCTION__, '2.0.0', Utils::class . '::background_position()' );

		$positions = Utils::background_position();
		if ( $key_only ) {
			return array_keys( $positions );
		}

		return $positions;
	}
}

if ( ! function_exists( 'carousel_slider_background_size' ) ) {
	/**
	 * @param bool $key_only
	 *
	 * @return array
	 */
	function carousel_slider_background_size( $key_only = false ) {
		_deprecated_function( __FUNCTION__, '2.0.0', Utils::class . '::background_size()' );

		$sizes = Utils::background_size();
		if ( $key_only ) {
			return array_keys( $sizes );
		}

		return $sizes;
	}
}

if ( ! function_exists( 'carousel_slider_default_settings' ) ) {
	function carousel_slider_default_settings() {
		_deprecated_function( __FUNCTION__, '2.0.0', Utils::class . '::default_settings()' );

		$options = Utils::default_settings();
		$options = json_decode( json_encode( $options ), false );

		return $options;
	}
}

if ( ! function_exists( 'carousel_slider_inline_style' ) ) {
	/**
	 * Get carousel slider inline style
	 *
	 * @param $carousel_id
	 */
	function carousel_slider_inline_style( $carousel_id ) {
		$id                      = $carousel_id;
		$_nav_color              = get_post_meta( $id, '_nav_color', true );
		$_nav_active_color       = get_post_meta( $id, '_nav_active_color', true );
		$_product_title_color    = get_post_meta( $id, '_product_title_color', true );
		$_product_btn_bg_color   = get_post_meta( $id, '_product_button_bg_color', true );
		$_product_btn_text_color = get_post_meta( $id, '_product_button_text_color', true );
		$content_sliders         = get_post_meta( $id, '_content_slider', true );

		$slide_type = get_post_meta( $id, '_slide_type', true );
		$slide_type = array_key_exists( $slide_type, CarouselSlider\Utils::slide_type() ) ? $slide_type : 'image-carousel';

		$_arrow_size = get_post_meta( $id, '_arrow_size', true );
		$_arrow_size = empty( $_arrow_size ) ? 48 : absint( $_arrow_size );

		$_bullet_size = get_post_meta( $id, '_bullet_size', true );
		$_bullet_size = empty( $_bullet_size ) ? 10 : absint( $_bullet_size );

		echo "<style type=\"text/css\">";

		// Arrows Nav
		echo "
            #id-{$id} .carousel-slider-nav-icon {
                fill: {$_nav_color}
            }
            #id-{$id} .carousel-slider-nav-icon:hover {
                fill: {$_nav_active_color}
            }
            #id-{$id} .owl-prev,
            #id-{$id} .owl-next,
            #id-{$id} .carousel-slider-nav-icon {
                height: {$_arrow_size}px;
                width: {$_arrow_size}px
            }
            #id-{$id}.arrows-outside .owl-prev {
                left: -{$_arrow_size}px
            }
            #id-{$id}.arrows-outside .owl-next {
                right: -{$_arrow_size}px
            }
        ";

		// Dots Nav
		echo "
		    #id-{$id} .owl-dots .owl-dot span {
                background-color: {$_nav_color};
                width: {$_bullet_size}px;
                height: {$_bullet_size}px;
            }
            #id-{$id} .owl-dots .owl-dot.active span,
            #id-{$id} .owl-dots .owl-dot:hover span {
                background-color: {$_nav_active_color}
            }
		";

		// Product Carousel Slider
		if ( $slide_type == 'product-carousel' ) {
			echo "
		        #id-{$id} .carousel-slider__product h3,
                #id-{$id} .carousel-slider__product .price {
                    color: {$_product_title_color};
                }

                #id-{$id} .carousel-slider__product a.add_to_cart_button,
                #id-{$id} .carousel-slider__product a.added_to_cart,
                #id-{$id} .carousel-slider__product a.quick_view,
                #id-{$id} .carousel-slider__product .onsale {
                    background-color: {$_product_btn_bg_color};
                    color: {$_product_btn_text_color};
                }

                #id-{$id} .carousel-slider__product .star-rating span:before {
                    color: {$_product_btn_bg_color};
                }
		    ";
		}

		// Content Carousel
		if ( $slide_type == 'hero-banner-slider' && $content_sliders ) {
			foreach ( $content_sliders as $slide_id => $slide ) {
				// Title Style
				$heading_font_size = ! empty( $slide['heading_font_size'] ) ? esc_attr( $slide['heading_font_size'] ) : 40;
				$heading_gutter    = ! empty( $slide['heading_gutter'] ) ? esc_attr( $slide['heading_gutter'] ) : '30px';
				$heading_color     = ! empty( $slide['heading_color'] ) ? esc_attr( $slide['heading_color'] ) : '#ffffff';
				echo "#id-{$id} .hero__cell-{$slide_id} .carousel-slider-hero__cell__heading {
					font-size: {$heading_font_size}px;
					margin-bottom: {$heading_gutter};
					color: {$heading_color};
				}";
				// Description Style
				$description_font_size = ! empty( $slide['description_font_size'] ) ? esc_attr( $slide['description_font_size'] ) : 20;
				$description_gutter    = ! empty( $slide['description_gutter'] ) ? esc_attr( $slide['description_gutter'] ) : '30px';
				$description_color     = ! empty( $slide['description_color'] ) ? esc_attr( $slide['description_color'] ) : '#ffffff';
				echo "#id-{$id} .hero__cell-{$slide_id} .carousel-slider-hero__cell__description{
					font-size: {$description_font_size}px;
					margin-bottom: {$description_gutter};
					color: {$description_color};
				}";
				// Button Style
				if ( isset( $slide['link_type'] ) && ( $slide['link_type'] == 'button' ) ) {

					$_btn_1_type          = ! empty( $slide['button_one_type'] ) ? esc_attr( $slide['button_one_type'] ) : 'normal';
					$_btn_1_bg_color      = ! empty( $slide['button_one_bg_color'] ) ? Sanitize::color( $slide['button_one_bg_color'] ) : '#00d1b2';
					$_btn_1_color         = ! empty( $slide['button_one_color'] ) ? Sanitize::color( $slide['button_one_color'] ) : '#ffffff';
					$_btn_1_border_width  = ! empty( $slide['button_one_border_width'] ) ? esc_attr( $slide['button_one_border_width'] ) : '0px';
					$_btn_1_border_radius = ! empty( $slide['button_one_border_radius'] ) ? esc_attr( $slide['button_one_border_radius'] ) : '3px';

					if ( $_btn_1_type == 'stroke' ) {
						echo "
						#id-{$id} .cs-hero-button-{$slide_id}-1 {
							border: {$_btn_1_border_width} solid {$_btn_1_bg_color};
							border-radius: {$_btn_1_border_radius};
							background-color: transparent;
							color: {$_btn_1_bg_color};
						}
						#id-{$id} .cs-hero-button-{$slide_id}-1:hover {
							border: {$_btn_1_border_width} solid {$_btn_1_bg_color};
							background-color: {$_btn_1_bg_color};
							color: {$_btn_1_color};
						}
					";
					} else {
						echo "
						#id-{$id} .cs-hero-button-{$slide_id}-1 {
							background-color: {$_btn_1_bg_color};
							border: {$_btn_1_border_width} solid {$_btn_1_bg_color};
							border-radius: {$_btn_1_border_radius};
							color: {$_btn_1_color};
						}
					";
					}

					$_btn_2_type          = ! empty( $slide['button_two_type'] ) ? esc_attr( $slide['button_two_type'] ) : 'normal';
					$_btn_2_bg_color      = ! empty( $slide['button_two_bg_color'] ) ? Sanitize::color( $slide['button_two_bg_color'] ) : '#00d1b2';
					$_btn_2_color         = ! empty( $slide['button_two_color'] ) ? Sanitize::color( $slide['button_two_color'] ) : '#ffffff';
					$_btn_2_border_width  = ! empty( $slide['button_two_border_width'] ) ? esc_attr( $slide['button_two_border_width'] ) : '0px';
					$_btn_2_border_radius = ! empty( $slide['button_two_border_radius'] ) ? esc_attr( $slide['button_two_border_radius'] ) : '3px';
					if ( $_btn_2_type == 'stroke' ) {
						echo "
						#id-{$id} .cs-hero-button-{$slide_id}-2 {
							border: {$_btn_2_border_width} solid {$_btn_2_bg_color};
							border-radius: {$_btn_2_border_radius};
							background-color: transparent;
							color: {$_btn_2_bg_color};
						}
						#id-{$id} .cs-hero-button-{$slide_id}-2:hover {
							border: {$_btn_2_border_width} solid {$_btn_2_bg_color};
							background-color: {$_btn_2_bg_color};
							color: {$_btn_2_color};
						}
					";
					} else {
						echo "
						#id-{$id} .cs-hero-button-{$slide_id}-2 {
							background-color: {$_btn_2_bg_color};
							border: {$_btn_2_border_width} solid {$_btn_2_bg_color};
							border-radius: {$_btn_2_border_radius};
							color: {$_btn_2_color};
						}
					";
					}
				}
			}
		}

		echo "</style>";
	}
}
