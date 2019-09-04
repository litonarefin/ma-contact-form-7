<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 9/3/19
	 * Description: Required Plugins and Promotional Admin Notice Class
	 */

	if (!defined('ABSPATH')) { exit; } // No, Direct access Sir !!!

	

	if( !class_exists('Master_Addons_Require_Class') ){
		class Master_Addons_Require_Class {

			private $plugin_slug;
			private $plugin_name;
			const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
			const MINIMUM_PHP_VERSION = '5.4';

			private static $instance = null;

			public static function get_instance() {
				if ( ! self::$instance ) {
					self::$instance = new self;

					self::$instance -> ma_el_init();
				}

				return self::$instance;
			}


			public function __construct() {

				add_action('plugins_loaded', [$this, 'ma_el_require_plugins_loaded']);
			}

			public function ma_el_init(){
				$this->ma_el_require_load_textdomain();
				// $this->ma_el_require_include_files();
			}

			public function get_plugin_info( $slug ) {

				// Create a empty array with variable name different based on plugin slug
				$transient_name = 'ma_el_' . $slug;

				/**
				 * Check if transient with the plugin data exists
				 */
				$info = get_transient( $transient_name );

				if ( empty( $info ) ) {

					/**
					 * Connect to WordPress.org using plugins_api
					 * About plugins_api -
					 * http://wp.tutsplus.com/tutorials/plugins/communicating-with-the-wordpress-org-plugin-api/
					 */
					require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
					$info = plugins_api( 'plugin_information', array( 'slug' => $slug ) );


					// Check for errors with the data returned from WordPress.org
					if ( ! $info or is_wp_error( $info ) ) {
						return null;
					}


					// Set a transient with the plugin data
					// Use Options API with auto update cron job in next version.
					set_transient( $transient_name, $info, 1 * DAY_IN_SECONDS );
				}

				return $info;
			}


			public function get_plugin_field( $slug, $field ) {

				// Fetch info
				$info = $this->get_plugin_info( $slug );

				if( ! is_object( $info ) || ! property_exists( $info, $field ) ) {
					return '';
				}

				return $info->{$field};
			}


			public function set_plugin_name( $plugin_name_value ) {
				$this->plugin_name = $plugin_name_value;
			}


			public function get_plugin_name() {
				return $this->plugin_name;
			}

			public function ma_el_require_load_textdomain(){
				return load_plugin_textdomain( 'master-addons' );
			}


			public function ma_el_require_plugins_loaded(){
				// Check if Elementor installed and activated
				if ( ! did_action( 'elementor/loaded' ) ) {
					add_action( 'admin_notices', array( $this, 'ma_el_require_admin_notice_missing_main_plugin' ) );
					return;
				}

				// Check for required Elementor version
				if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
					add_action( 'admin_notices', array( $this, 'ma_el_require_admin_notice_minimum_elementor_version'
					) );
					return;
				}

				// Check for required PHP version
				if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
					add_action( 'admin_notices', array( $this, 'ma_el_require_admin_notice_minimum_php_version' ) );
					return;
				}
			}

			public function is_elementor_activated( $plugin_path = 'elementor/elementor.php' ) {

				$installed_plugins_list = get_plugins();

				return isset( $installed_plugins_list[ $plugin_path ] );
			}


			public function ma_el_require_admin_notice_missing_main_plugin() {

				$plugin = 'elementor/elementor.php';


				if ( $this->is_elementor_activated() ) {
					if ( ! current_user_can( 'activate_plugins' ) ) {
						return;
					}
					$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
					$message = sprintf( '<b>%1$s</b> requires <b>"Elementor"</b> plugin to be active. Please activate <b>"Elementor"</b> to continue.',
						$this->get_plugin_name(), $this->is_elementor_activated(),MA_CF7_TD   );
					$button_text = __( 'Activate Elementor', MA_CF7_TD );

				} else {

					if ( ! current_user_can( 'install_plugins' ) ) {
						return;
					}

					$activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
					$message = sprintf(  __('<b>%3$s</b> requires %1$s "Elementor" %2$s plugin to be installed and activated. Please Install Elementor to Explore more.', MA_CF7_TD) , '<strong>', '</strong>', $this->get_plugin_name() );
					$button_text = sprintf( __('Install Elementor',MA_CF7_TD) );
				}

				$button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $message , $button );


			}


			public function ma_el_require_admin_notice_minimum_elementor_version() {

				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}

				$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
					esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', MA_CF7_TD ),
					'<strong>' . sprintf( '<b>%1$s</b> for Elementor', $this->get_plugin_name(), MA_CF7_TD ) . '</strong>',
					'<strong>' . esc_html__( 'Elementor', MA_CF7_TD ) . '</strong>',
					self::MINIMUM_ELEMENTOR_VERSION
				);

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
			}

			public function ma_el_require_admin_notice_minimum_php_version() {
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}

				$message = sprintf(
				/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
					esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', MA_CF7_TD ),
					'<strong>' . sprintf( '<b>%1$s</b> for Elementor', $this->get_plugin_name(), MA_CF7_TD ) . '</strong>',
					'<strong>' . esc_html__( 'PHP', MA_CF7_TD ) . '</strong>',
					self::MINIMUM_PHP_VERSION
				);

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
			}

			// public function ma_el_require_include_files(){

			// }



		}

		// Master_Addons_Require_Class::get_instance();
	}
