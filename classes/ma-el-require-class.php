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

//				$this->ma_el_require_include_files();
//				$this->is_elementor_installed();
				$this->ma_el_require_constants();
				$this->ma_el_require_load_textdomain();
				add_action('plugins_loaded', [$this, 'ma_el_require_plugins_loaded']);


				/* Admin notice for asking ratings and Required Plugins */
				add_action( 'admin_notices', array( $this, 'ma_el_promotional_offer' ) );
				add_action( 'admin_notices' , array( $this, 'ma_el_review_notice_message' ) );
				add_action( 'wp_ajax_ma_el_dismiss_offer_notice', array( $this, 'ma_el_dismiss_offer_notice' ));
				add_action( 'wp_ajax_ma_el_review_notice', array( $this, 'ma_el_review_notice' ) );

			}

			public function ma_el_promotional_offer(){

			}

			public function ma_el_review_notice_message(){

			}

			public function ma_el_dismiss_offer_notice(){

			}

			public function ma_el_review_notice(){

			}

			public function ma_el_require_constants(){

				// Master Addons Text Domain
				if ( ! defined( 'MA_EL_TD' ) ) {
					define( 'MA_EL_TD', $this->ma_el_require_load_textdomain() );
				}
			}

			/**
			 * @param string $slug The WordPress.org slug of the plugin
			 * @return StdClass
			 */
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

			/**
			 * Get a specific field
			 *
			 * @param string $slug The WordPress.org slug of the plugin
			 * @param string $field The field you want to retrieve
			 *
			 * @return string
			 */
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

			public function ma_el_init(){

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
						$this->get_plugin_name(), $this->is_elementor_activated(),MA_EL_TD   );
					$button_text = __( 'Activate Elementor', MA_EL_TD );

				} else {

					if ( ! current_user_can( 'install_plugins' ) ) {
						return;
					}

					$activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
					$message = sprintf(  __('<b>%3$s</b> requires %1$s "Elementor" %2$s plugin to be installed and activated. Please Install Elementor to Explore more.', MA_EL_TD) , '<strong>', '</strong>', $this->get_plugin_name() );
					$button_text = sprintf( __('Install Elementor',MA_EL_TD) );
				}

				$button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $message , $button );




				$get_plugins_list = get_plugins();
				$master_addons = 'master-addons/master-elementor-addons.php';


				if ( isset($get_plugins_list[ $master_addons ]["Name"]) == "Master Addons for Elementor" ) {
					if ( ! current_user_can( 'activate_plugins' ) ) {
						return;
					}
					$master_addons_activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $master_addons . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $master_addons );
					$master_addons_message = sprintf( '<b>%1$s</b> requires <b>"Master Addons"</b> plugin to be active. Please activate "Master Addons" to continue.',
						$this->get_plugin_name(), $this->is_elementor_activated(),MA_EL_TD   );
					$master_addons_button_text = __( 'Activate Master Addons', MA_EL_TD );

				} else {

					if ( ! current_user_can( 'install_plugins' ) ) {
						return;
					}

					$master_addons_activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=master-addons' ), 'install-master-addons' );
					$master_addons_message = sprintf(  __('<b>%3$s</b> requires "Master Addons" plugin to be Installed and Activated. Please Install Master Addons to Explore more.', MA_EL_TD) , '<strong>', '</strong>', $this->get_plugin_name() );
					$master_addons_button_text = sprintf( __('Install Master Addons',MA_EL_TD) );
				}

				$master_addons_button = '<p><a href="' . $master_addons_activation_url . '" class="button-primary">' . $master_addons_button_text . '</a></p>';

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $master_addons_message , $master_addons_button );

			}


			public function ma_el_require_admin_notice_minimum_elementor_version() {

				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}

				$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
					esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', MA_EL_TD ),
					'<strong>' . sprintf( '<b>%1$s</b> for Elementor', $this->get_plugin_name(), MA_EL_TD ) . '</strong>',
					'<strong>' . esc_html__( 'Elementor', MA_EL_TD ) . '</strong>',
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
					esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', MA_EL_TD ),
					'<strong>' . sprintf( '<b>%1$s</b> for Elementor', $this->get_plugin_name(), MA_EL_TD ) . '</strong>',
					'<strong>' . esc_html__( 'PHP', MA_EL_TD ) . '</strong>',
					self::MINIMUM_PHP_VERSION
				);

				printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
			}




		}

//		Master_Addons_Require_Class::get_instance();
	}
