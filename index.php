<?php
	/*
	Plugin Name: Master Contact Form 7 - Styler for Elementor
	Plugin URI: https://master-addons.com
	Description: Easily Customizable Contact Form 7 Style for Elementor Page Builder Plugin
	Version: 1.0.0
	Author: Liton Arefin
	Author URI: http://master-addons.com
	License: GPL2
	@Author: Litonice13, jwthemeltd
*/


	if (!defined('ABSPATH')) { exit; } // No, Direct access Sir !!!

	if( !class_exists('Master_Contact_Form_7_Styler') ) {
		final class Master_Contact_Form_7_Styler {

			const VERSION = "1.0.0";
			const MINIMUM_PHP_VERSION = '5.4';
			const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
			public static $plugin_name = 'Master Contact Form 7 Style';

			private static $instance = null;

			private static $plugin_path;
			private static $plugin_url;
			private static $plugin_slug;
			public static $plugin_dir_url;

			public static function get_instance() {
				if ( ! self::$instance ) {
					self::$instance = new self;

					self::$instance -> ma_cf7_init();
				}

				return self::$instance;
			}

			public function __construct() {
				$this->ma_cf7_constants();
				$this->ma_cf7_include_files();

				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'ma_cf7_plugin_actions_links' ] );

			}

			public function ma_cf7_init(){

			}

			public function ma_cf7_constants(){

				/*
				 *	Defined Constants
				 */
				if ( ! defined( 'MA_CF7_PLUGIN' ) ) {
					define( 'MA_CF7_PLUGIN', __FILE__ );
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


			public function ma_cf7_load_textdomain(){
				return load_plugin_textdomain( 'ma-cf7' );
			}



			// Plugin URL
			public static function mela_plugin_url() {

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
				include_once MA_CF7_PLUGIN_PATH . '/classes/ma-el-require-class.php';

				$ma_el_cf7 = new Master_Addons_Require_Class();
				$ma_el_cf7->set_plugin_name( self::$plugin_name );

			}

			public function ma_cf7_plugin_actions_links($links){

				if ( is_admin() ) {
					$links[] = '<a href="https://master-addons.com/contact-us" target="_blank">' . esc_html__( 'Support', MELA_TD ) . '</a>';
					$links[] = '<a href="https://master-addons.com/docs/" target="_blank">' . esc_html__( 'Documentation',
							MELA_TD ) . '</a>';
				}

				return $links;

			}





		}

		Master_Contact_Form_7_Styler::get_instance();

	}