<?php
/**
 * Plugin Name: Carousel Slider
 * Plugin URI: http://wordpress.org/plugins/carousel-slider
 * Description: <strong>Carousel Slider</strong> allows you to create beautiful, touch enabled, responsive carousels and sliders. It let you create SEO friendly Image carousel from Media Library or from custom URL, Video carousel using Youtube and Vimeo video, Post carousel, Hero banner slider and various types of WooCommerce products carousels.
 * Version: 1.9.4
 * Author: Sayful Islam
 * Author URI: https://sayfulislam.com
 * Requires at least: 4.8
 * Tested up to: 5.5
 * Requires PHP: 5.6
 *
 * WC requires at least: 3.0
 * WC tested up to: 4.3
 *
 * Text Domain: carousel-slider
 *
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package Carousel_Slider
 * @author Sayful Islam
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Carousel_Slider' ) ) {

	final class Carousel_Slider {

		/**
		 * Plugin name slug
		 *
		 * @var string
		 */
		private $plugin_name = 'carousel-slider';

		/**
		 * Plugin custom post type
		 *
		 * @var string
		 */
		private $post_type = 'carousels';

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		private $version = '1.9.4';

		/**
		 * Minimum PHP version required
		 *
		 * @var string
		 */
		private $min_php = '5.6';

		/**
		 * Holds various class instances
		 *
		 * @var array
		 */
		private $container = array();

		/**
		 * The instance of the class
		 *
		 * @var self
		 */
		protected static $instance;

		/**
		 * Main Carousel_Slider Instance
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @return Carousel_Slider - Main instance
		 * @since 1.6.0
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();

				do_action( 'carousel_slider_init' );

				self::$instance->define_constants();

				// Check if PHP version is supported for our plugin
				if ( ! self::$instance->is_supported_php() ) {
					register_activation_hook( __FILE__, array( self::$instance, 'auto_deactivate' ) );
					add_action( 'admin_notices', array( self::$instance, 'php_version_notice' ) );

					return self::$instance;
				}

				self::$instance->autoload_classes();
				add_action( 'plugins_loaded', [ self::$instance, 'include_classes' ] );

				register_activation_hook( __FILE__, array( self::$instance, 'activation' ) );
				register_deactivation_hook( __FILE__, array( self::$instance, 'deactivation' ) );

				add_action( 'init', array( self::$instance, 'load_textdomain' ) );

				do_action( 'carousel_slider_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Define plugin constants
		 */
		public function define_constants() {
			define( 'CAROUSEL_SLIDER_VERSION', $this->version );
			define( 'CAROUSEL_SLIDER_POST_TYPE', $this->post_type );
			define( 'CAROUSEL_SLIDER_FILE', __FILE__ );
			define( 'CAROUSEL_SLIDER_PATH', dirname( CAROUSEL_SLIDER_FILE ) );
			define( 'CAROUSEL_SLIDER_INCLUDES', CAROUSEL_SLIDER_PATH . '/includes' );
			define( 'CAROUSEL_SLIDER_TEMPLATES', CAROUSEL_SLIDER_PATH . '/templates' );
			define( 'CAROUSEL_SLIDER_WIDGETS', CAROUSEL_SLIDER_PATH . '/widgets' );
			define( 'CAROUSEL_SLIDER_URL', plugins_url( '', CAROUSEL_SLIDER_FILE ) );
			define( 'CAROUSEL_SLIDER_ASSETS', CAROUSEL_SLIDER_URL . '/assets' );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param string $name
		 * @param string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Load plugin classes
		 */
		public function autoload_classes() {
			spl_autoload_register( function ( $class ) {

				// project-specific namespace prefix
				$prefix = 'CarouselSlider\\';

				// base directory for the namespace prefix
				$base_dir = CAROUSEL_SLIDER_PATH . '/classes/';

				// does the class use the namespace prefix?
				$len = strlen( $prefix );
				if ( strncmp( $prefix, $class, $len ) !== 0 ) {
					// no, move to the next registered autoloader
					return;
				}

				// get the relative class name
				$relative_class = substr( $class, $len );

				// replace the namespace prefix with the base directory, replace namespace
				// separators with directory separators in the relative class name, append
				// with .php
				$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

				// if the file exists, require it
				if ( file_exists( $file ) ) {
					require $file;
				}
			} );
		}

		/**
		 * Include admin and front facing files
		 */
		public function include_classes() {
			require_once CAROUSEL_SLIDER_INCLUDES . '/functions-carousel-slider.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-product.php';
			require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-preview.php';
			require_once CAROUSEL_SLIDER_WIDGETS . '/widget-carousel_slider.php';

			$this->container['assets'] = CarouselSlider\Assets::init();
			$this->container['ajax']   = CarouselSlider\Ajax::init();

			if ( $this->is_request( 'admin' ) ) {
				$this->container['admin']           = CarouselSlider\Admin\Admin::init();
				$this->container['visual_composer'] = CarouselSlider\Integration\VisualComposer::init();

				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-form.php';
				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-admin.php';
				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-meta-box.php';
				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-hero-carousel.php';
				require_once CAROUSEL_SLIDER_INCLUDES . '/class-carousel-slider-gutenberg-block.php';
			}

			if ( $this->is_request( 'frontend' ) ) {
				$this->container['structured_post']    = CarouselSlider\StructuredData\BlogPosting::init();
				$this->container['structured_image']   = CarouselSlider\StructuredData\ImageObject::init();
				$this->container['structured_product'] = CarouselSlider\StructuredData\Product::init();
			}

			require_once CAROUSEL_SLIDER_PATH . '/shortcodes/class-carousel-slider-shortcode.php';
			require_once CAROUSEL_SLIDER_PATH . '/shortcodes/class-carousel-slider-deprecated-shortcode.php';
		}

		/**
		 * Load plugin textdomain
		 */
		public function load_textdomain() {
			$locale_file = sprintf( '%1$s-%2$s.mo', 'carousel-slider', get_locale() );
			$global_file = join( DIRECTORY_SEPARATOR, array( WP_LANG_DIR, 'carousel-slider', $locale_file ) );

			// Look in global /wp-content/languages/carousel-slider folder
			if ( file_exists( $global_file ) ) {
				load_textdomain( $this->plugin_name, $global_file );
			}
		}

		/**
		 * To be run when the plugin is activated
		 *
		 * @return void
		 */
		public function activation() {
			CarouselSlider\Activator::activate();
			do_action( 'carousel_slider_activation' );
			flush_rewrite_rules();
		}

		/**
		 * To be run when the plugin is deactivated
		 *
		 * @return void
		 */
		public function deactivation() {
			do_action( 'carousel_slider_deactivation' );
			flush_rewrite_rules();
		}

		/**
		 * Show notice about PHP version
		 *
		 * @return void
		 */
		public function php_version_notice() {

			if ( $this->is_supported_php() || ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$error = __( 'Your installed PHP Version is: ', 'carousel-slider' ) . PHP_VERSION . '. ';
			$error .= sprintf( __( 'The Carousel Slider plugin requires PHP version %s or greater.',
				'carousel-slider' ), $this->min_php );
			?>
			<div class="error">
				<p><?php printf( $error ); ?></p>
			</div>
			<?php
		}

		/**
		 * Bail out if the php version is lower than
		 *
		 * @return void
		 */
		public function auto_deactivate() {
			if ( $this->is_supported_php() ) {
				return;
			}

			deactivate_plugins( plugin_basename( __FILE__ ) );

			$error = '<h1>' . __( 'An Error Occurred', 'carousel-slider' ) . '</h1>';
			$error .= '<h2>' . __( 'Your installed PHP Version is: ', 'carousel-slider' ) . PHP_VERSION . '</h2>';
			$error .= '<p>' . sprintf( __( 'The Carousel Slider plugin requires PHP version %s or greater',
					'carousel-slider' ), $this->min_php ) . '</p>';
			$error .= '<p>' . sprintf( __( 'The version of your PHP is %s unsupported and old %s. ',
					'carousel-slider' ),
					'<a href="http://php.net/supported-versions.php" target="_blank"><strong>',
					'</strong></a>'
				);
			$error .= __( 'You should update your PHP software or contact your host regarding this matter.',
					'carousel-slider' ) . '</p>';

			wp_die( $error, __( 'Plugin Activation Error', 'carousel-slider' ), array( 'back_link' => true ) );
		}

		/**
		 * What type of request is this?
		 *
		 * @param string $type admin, ajax, cron or frontend.
		 *
		 * @return bool
		 */
		public function is_request( $type ) {
			switch ( $type ) {
				case 'admin':
					return is_admin();
				case 'ajax':
					return defined( 'DOING_AJAX' );
				case 'cron':
					return defined( 'DOING_CRON' );
				case 'frontend':
					return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
			}

			return false;
		}

		/**
		 * Check if the PHP version is supported
		 *
		 * @param null $min_php
		 *
		 * @return bool
		 */
		private function is_supported_php( $min_php = null ) {
			$min_php = $min_php ? $min_php : $this->min_php;

			if ( version_compare( PHP_VERSION, $min_php, '<=' ) ) {
				return false;
			}

			return true;
		}
	}
}

/**
 * Begins execution of the plugin.
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
Carousel_Slider::instance();
