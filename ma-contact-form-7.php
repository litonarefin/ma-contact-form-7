<?php
	/*
	Plugin Name: Master Contact Form 7 - Styler for Elementor
	Plugin URI: https://master-addons.com
	Description: Easily Customizable Contact Form 7 Style for Elementor Page Builder Plugin
	Version: 1.4.0
	Author: Liton Arefin
	Author URI: http://master-addons.com
	License: GPL2
*/


	if (!defined('ABSPATH')) { exit; } // No, Direct access Sir !!!

	if( !class_exists('Master_Contact_Form_7_Styler') ) {
		final class Master_Contact_Form_7_Styler {

			const VERSION = "1.0.0";
			const MINIMUM_PHP_VERSION = '5.4';
			const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
			public static $plugin_name = 'Master Contact Form 7 Styler';
//			public static $plugin_slug = 'ma-contact-form-7';
			public static $plugin_slug = 'elementor';

			private static $instance = null;

			private static $plugin_path;
			private static $plugin_url;
//			private static $plugin_slug;
			public static $plugin_dir_url;

			public static function get_instance() {
				if ( ! self::$instance ) {
					self::$instance = new self;

					self::$instance -> ma_cf7_init();
				}

				return self::$instance;
			}

			public function __construct() {

				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'ma_cf7_plugin_actions_links' ] );

				add_action( 'elementor/init', [ $this, 'ma_cf7_category' ] );


				// Enqueue Styles and Scripts
				add_action( 'wp_enqueue_scripts', [ $this, 'ma_cf7_enqueue_scripts' ], 20 );

				// Elementor Dependencies

				// add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'ma_cf7_editor_styles' ] );

				// Add Elementor Widgets
				add_action( 'elementor/widgets/widgets_registered', [ $this, 'ma_cf7_init_widgets' ] );


			}

			public function ma_cf7_init(){

				$this->ma_cf7_constants();
				$this->ma_cf7_include_files();

			}

			public function ma_cf7_constants(){

				/*
				 *	Defined Constants
				 */
				if ( ! defined( 'MA_CF7_PLUGIN' ) ) {
					define( 'MA_CF7_PLUGIN', __FILE__ );
				}
				
				if ( ! defined( 'MA_CF7_PLUGIN_URL' ) ) {
					define( 'MA_CF7_PLUGIN_URL', self::ma_cf7_plugin_url() );
				}

				if ( ! defined( 'MA_CF7_PLUGIN_PATH' ) ) {
					define( 'MA_CF7_PLUGIN_PATH', self::ma_cf7_plugin_path() );
				}

				if ( ! defined( 'MA_CF7_PLUGIN_DIR' ) ) {
					define( 'MA_CF7_PLUGIN_DIR', untrailingslashit( dirname( MA_CF7_PLUGIN ) ) );
				}

				if ( ! defined( 'MA_CF7_LOCATION' ) ) {
					define( 'MA_CF7_LOCATION',plugin_dir_url( MA_CF7_PLUGIN ) );
				}

				if ( ! defined( 'MA_CF7_PLUGIN_VER' ) ) {
					define( 'MA_CF7_PLUGIN_VER', self::VERSION );
				}

				if ( ! defined( 'MA_CF7_REQ_PLUGIN' ) ) {
					define( 'MA_CF7_REQ_PLUGIN', 'contact-form-7/wp-contact-form-7.php' );
				}

				if ( ! defined( 'MA_CF7_TD' ) ) {
					define( 'MA_CF7_TD', $this->ma_cf7_load_textdomain() );
				}

			}


			public function ma_cf7_enqueue_scripts(){
				
				wp_enqueue_style( 'ma-contact-form-7', MA_CF7_PLUGIN_URL . '/css/ma-cf7.css' );

			}

			public function ma_cf7_editor_styles(){

			}


			public function ma_cf7_init_widgets() {

				if ( function_exists( 'wpcf7' ) ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				
				if ( is_plugin_active( 'elementor/elementor.php' ) ) {
						require_once MA_CF7_PLUGIN_PATH  .'/addon/ma-cf7.php';
					}
				}

				add_action( 'tgmpa_register', [$this, 'ma_cf7_register_required_plugins'] );


				
			}

			public function ma_cf7_register_required_plugins(){

				/**
				 * Array of plugin arrays. Required keys are name, slug and required.
				 * If the source is NOT from the .org repo, then source is also required.
				 */

				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				if ( is_plugin_active( 'elementor/elementor.php' ) ) {

					$plugins = array(
							array(
								'name'      		 => esc_html__( 'Master Addons for Elementor', 'master-addons' ),
								'slug'      		 => esc_html__( 'master-addons', 'master-addons' ),
								'required'  		 => true,
								'force_activation'   => false,
							)
						);
				}


				if ( !is_plugin_active( MA_CF7_REQ_PLUGIN ) ) {

					$plugins = array(
							array(
								'name'      		 => esc_html__( 'Master Addons for Elementor', 'master-addons' ),
								'slug'      		 => esc_html__( 'master-addons', 'master-addons' ),
								'required'  		 => true,
								'force_activation'   => false,
							),						
							array(
								'name'      		 => esc_html__( 'Contact Form 7', 'master-addons' ),
								'slug'      		 => esc_html__( 'contact-form-7', 'master-addons' ),
								'required'  		 => true,
								'force_activation'   => false,
							)
						);
				}

				$config = array(
					'id'           => 'ma-el-cf7',                 // Unique ID for hashing notices for multiple instances of TGMPA.
					'default_path' => '',                      // Default absolute path to bundled plugins.
					'menu'         => 'tgmpa-install-plugins', // Menu slug.
					'parent_slug'  => 'plugins.php',            // Parent menu slug.
					'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
					'has_notices'  => true,                    // Show admin notices or not.
					'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
					'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
					'is_automatic' => false,                   // Automatically activate plugins after installation or not.
					'message'      => '',                      // Message to output right before the plugins table.
				);

				tgmpa( $plugins, $config );

				//tgmpa( $plugins);

			

			}

			function ma_cf7_category() {

				\Elementor\Plugin::instance()->elements_manager->add_category(
					'master-addons',
					[
						'title' => esc_html__( 'Master Addons', MA_CF7_TD ),
						'icon'  => 'font',
					], 1 );
			}

			public function ma_cf7_load_textdomain(){
				return load_plugin_textdomain( 'ma-cf7' );
			}


			// Plugin URL
			public static function ma_cf7_plugin_url() {

				if ( self::$plugin_url ) {
					return self::$plugin_url;
				}

				return self::$plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );

			}

			// Plugin Path
			public static function ma_cf7_plugin_path() {
				if ( self::$plugin_path ) {
					return self::$plugin_path;
				}

				return self::$plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
			}

			public function ma_cf7_include_files(){
				
				include_once MA_CF7_PLUGIN_PATH . '/inc/functions.php';
				include_once MA_CF7_PLUGIN_PATH . '/classes/ma-el-require-class.php';
				

				$ma_el_cf7 = new Master_Addons_Require_Class();
				$ma_el_cf7->set_plugin_name( self::$plugin_name );

				include( plugin_dir_path( __FILE__ ) . 'classes/class-tgm-plugin-activation.php');
				
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				//Is Elementor Activated
				if ( is_plugin_active( 'elementor/elementor.php' ) ) {								
					add_action( 'tgmpa_register', [$this, 'ma_cf7_register_required_plugins']);
				}


			}

			public function ma_cf7_plugin_actions_links($links){

				if ( is_admin() ) {
					$links[] = '<a href="https://master-addons.com/contact-us" target="_blank">' . esc_html__( 'Support', MA_CF7_TD ) . '</a>';
					$links[] = '<a href="https://master-addons.com/docs/" target="_blank">' . esc_html__( 'Documentation',
							MA_CF7_TD ) . '</a>';
				}

				return $links;

			}





		}

		Master_Contact_Form_7_Styler::get_instance();

	}