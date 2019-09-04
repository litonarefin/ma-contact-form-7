<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 9/3/19
	 * Description: Required Plugins and Promotional Admin Notice Class
	 */

	if (!defined('ABSPATH')) { exit; } // No, Direct access Sir !!!

<<<<<<< HEAD
	
=======

>>>>>>> 9e1e7a08c2c96349592e60986e11ec799a7b1a27

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


				/* Admin notice for asking ratings and Required Plugins */
				add_action( 'admin_notices', array( $this, 'ma_el_promotional_offer' ) );
				add_action( 'admin_notices' , array( $this, 'ma_el_review_notice_message' ) );
				add_action( 'wp_ajax_ma_el_promotional_offer_notice', array( $this, 'ma_el_dismiss_offer_notice' ));
				add_action( 'wp_ajax_ma_el_review_notice', array( $this, 'ma_el_review_notice' ) );

			}

			public function ma_el_init(){
				$this->ma_el_require_constants();
				$this->ma_el_require_load_textdomain();
				// $this->ma_el_require_include_files();
			}


			public function ma_el_promotional_offer(){
				// Show only to Admins
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}

				// 2018-03-26 23:59:00
				if ( time() > 1543276740 ) {
					return;
				}

				// check if wpuf is showing banner
				if ( class_exists( 'MA_EL_Admin_Promotion' ) ) {
					return;
				}

				// check if it has already been dismissed
				$hide_notice = get_option( 'ma_el_dismiss_offer_notice', 'no' );

				if ( 'hide' == $hide_notice ) {
					return;
				}

//				 $product_text = (  ma_el_fs()->can_use_premium_code() ) ? __( 'Pro upgrade and all extensions, ', 'master-addons' ) : __( 'all extensions, ', 'master-addons' );

//				 $offer_msg  = __( '<h2><span class="dashicons dashicons-awards"></span> Jewel Theme\'s 6th Birthday
// Offer</h2>', 'master-addons' );
//				$offer_msg  = __( '<p>
//                                <strong class="highlight-text" style="font-size: 18px">33&#37; flat discount on all our products</strong><br>
//                                Save money this holiday season while supercharging your WordPress site with plugins that were made to empower you.
//                                <br>
//                                Offer ending soon!
//                            </p>', 'master-addons' );

				?>
                <div class="notice is-dismissible" id="master-addons-promotional-offer-notice">
                    <table>
                        <tbody>
                        <tr>
                            <td class="image-container">
                                <img src="https://ps.w.org/master-addons/assets/icon-256x256.png" alt="">
                            </td>
                            <td class="message-container">
								<?php echo $offer_msg; ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <span class="dashicons dashicons-megaphone"></span>
                    <a href="https://wedevs.com/coupons/?utm_campaign=black_friday_cyber_monday&utm_medium=banner
					&utm_source=inside_plugin" class="button button-primary promo-btn" target="_blank"><?php _e( 'Get the Offer', 'master-addons' ); ?></a>
                </div><!-- #master-addons-promotional-offer-notice -->

                <style>
                    #master-addons-promotional-offer-notice {
                        background-image: linear-gradient(35deg,#00c9ff 0%, #92fe9d 100%) !important;
                        border: 0px;
                        padding: 0;
                        opacity: 0;
                    }

                    .wrap > #master-addons-promotional-offer-notice {
                        opacity: 1;
                    }

                    #master-addons-promotional-offer-notice table {
                        border-collapse: collapse;
                        width: 100%;
                    }

                    #master-addons-promotional-offer-notice table td {
                        padding: 0;
                    }

                    #master-addons-promotional-offer-notice table td.image-container {
                        background-color: #ebf0f4;
                        vertical-align: middle;
                        width: 95px;
                    }

                    #master-addons-promotional-offer-notice img {
                        max-width: 100%;
                        max-height: 100px;
                        vertical-align: middle;
                    }

                    #master-addons-promotional-offer-notice table td.message-container {
                        padding: 0 10px;
                    }

                    #master-addons-promotional-offer-notice h2{
                        color: rgba(250, 250, 250, 0.77);
                        margin-bottom: 10px;
                        font-weight: normal;
                        margin: 16px 0 14px;
                        -webkit-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        -moz-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        -o-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                    }

                    #master-addons-promotional-offer-notice h2 span {
                        position: relative;
                        top: 0;
                    }

                    #master-addons-promotional-offer-notice p{
                        color: rgba(250, 250, 250, 0.77);
                        font-size: 14px;
                        margin-bottom: 10px;
                        -webkit-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        -moz-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        -o-text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                        text-shadow: 0.1px 0.1px 0px rgba(250, 250, 250, 0.24);
                    }

                    #master-addons-promotional-offer-notice p strong.highlight-text{
                        color: #fff;
                    }

                    #master-addons-promotional-offer-notice p a {
                        color: #fafafa;
                    }

                    #master-addons-promotional-offer-notice .notice-dismiss:before {
                        color: #fff;
                    }

                    #master-addons-promotional-offer-notice span.dashicons-megaphone {
                        position: absolute;
                        bottom: 46px;
                        right: 248px;
                        color: rgba(253, 253, 253, 0.29);
                        font-size: 96px;
                        transform: rotate(-21deg);
                    }

                    #master-addons-promotional-offer-notice a.promo-btn{
                        background: #149269;
                        border-color: #149269 #149269 #149269;
                        box-shadow: 0 1px 0 #149269;
                        /*color: #45E2D0;*/
                        text-decoration: none;
                        text-shadow: none;
                        position: absolute;
                        top: 30px;
                        right: 26px;
                        height: 40px;
                        line-height: 40px;
                        width: 130px;
                        text-align: center;
                        font-weight: 600;
                    }

                </style>

                <script type='text/javascript'>
                    jQuery('body').on('click', '#master-addons-promotional-offer-notice .notice-dismiss', function(e) {
                        e.preventDefault();

                        wp.ajax.post('master-addons-dismiss-promotional-offer-notice', {
                            dismissed: true
                        });
                    });
                </script>
				<?php

			}


			public function ma_el_review_notice_message(){
// Show only to Admins
				if ( ! current_user_can( 'manage_options' ) ) {
					return;
				}

				$dismiss_notice  = get_option( 'ma_el_review_notice_dismiss', 'no' );
				$activation_time = get_option( 'ma_el_installed' );
				$total_entries   = 1000;

				// check if it has already been dismissed
				// and don't show notice in 15 days of installation, 1296000 = 15 Days in seconds
				if ( 'yes' == $dismiss_notice ) {
					return;
				}

				if ( (time() - $activation_time < 1296000) && $total_entries < 50 ) {
					return;
				}

				?>
                <div id="master-addons-review-notice" class="master-addons-review-notice">
                    <div class="master-addons-review-thumbnail">
                        <img src="https://ps.w.org/master-addons/assets/icon-256x256.png" alt="">
                    </div>
                    <div class="master-addons-review-text">
						<?php if( $total_entries >= 50 ) : ?>
                            <h3><?php _e( 'Enjoying <strong>Master Addons</strong>?', 'master-addons' ) ?></h3>
                            <p><?php _e( 'Seems like you are enjoying <strong>Master Addons</strong>. Would you please show us a little Love by rating us in the <a href="https://wordpress.org/support/plugin/master-addons/reviews/#postform" target="_blank"><strong>WordPress.org</strong></a>?', 'master-addons' ) ?></p>
						<?php else: ?>
                            <h3><?php _e( 'Enjoying <strong>Master Addons</strong>?', 'master-addons' ) ?></h3>
                            <p><?php _e( 'Hope that you had a neat and snappy experience with the tool. Would you 
							please show us a little love by rating us in the <a 
							href="https://wordpress.org/support/plugin/master-addons/reviews/#postform" target="_blank"><strong>WordPress.org</strong></a>?', 'master-addons' ) ?></p>
						<?php endif; ?>

                        <ul class="master-addons-review-ul">
                            <li><a href="https://wordpress.org/support/plugin/master-addons/reviews/#postform"
                                   target="_blank"><span class="dashicons dashicons-external"></span><?php _e( 'Sure! I\'d love to!', 'master-addons' ) ?></a></li>
                            <li><a href="#" class="notice-dismiss"><span class="dashicons dashicons-smiley"></span><?php _e( 'I\'ve already left a review', 'master-addons' ) ?></a></li>
                            <li><a href="#" class="notice-dismiss"><span class="dashicons dashicons-dismiss"></span><?php _e( 'Never show again', 'master-addons' ) ?></a></li>
                        </ul>
                    </div>
                </div>

                <style type="text/css">
                    #master-addons-review-notice .notice-dismiss{
                        padding: 0 0 0 26px;
                    }

                    #master-addons-review-notice .notice-dismiss:before{
                        display: none;
                    }

                    #master-addons-review-notice.master-addons-review-notice {
                        padding: 15px 15px 15px 0;
                        background-color: #fff;
                        border-radius: 3px;
                        margin: 20px 20px 0 0;
                        border-left: 4px solid transparent;
                    }

                    #master-addons-review-notice .master-addons-review-thumbnail {
                        width: 114px;
                        float: left;
                        line-height: 80px;
                        text-align: center;
                        border-right: 4px solid transparent;
                    }

                    #master-addons-review-notice .master-addons-review-thumbnail img {
                        width: 60px;
                        vertical-align: middle;
                    }

                    #master-addons-review-notice .master-addons-review-text {
                        overflow: hidden;
                    }

                    #master-addons-review-notice .master-addons-review-text h3 {
                        font-size: 24px;
                        margin: 0 0 5px;
                        font-weight: 400;
                        line-height: 1.3;
                    }

                    #master-addons-review-notice .master-addons-review-text p {
                        font-size: 13px;
                        margin: 0 0 5px;
                    }

                    #master-addons-review-notice .master-addons-review-ul {
                        margin: 0;
                        padding: 0;
                    }

                    #master-addons-review-notice .master-addons-review-ul li {
                        display: inline-block;
                        margin-right: 15px;
                    }

                    #master-addons-review-notice .master-addons-review-ul li a {
                        display: inline-block;
                        color: #4b00e7;
                        text-decoration: none;
                        padding-left: 26px;
                        position: relative;
                    }

                    #master-addons-review-notice .master-addons-review-ul li a span {
                        position: absolute;
                        left: 0;
                        top: -2px;
                    }
                </style>
                <script type='text/javascript'>
                    jQuery('body').on('click', '#master-addons-review-notice .notice-dismiss', function(e) {
                        e.preventDefault();
                        jQuery("#master-addons-review-notice").hide();

                        wp.ajax.post('ma_el_review_notice', {
                            dismissed: true
                        });
                    });
                </script>
				<?php
			}


			public function ma_el_dismiss_offer_notice(){
				if ( ! empty( $_POST['dismissed'] ) ) {
					$offer_key = 'ma_el_promotional_offer_notice';
					update_option( $offer_key, 'hide' );
				}
			}


			public function ma_el_review_notice(){
				if ( ! empty( $_POST['dismissed'] ) ) {
					update_option( 'ma_el_review_notice_dismiss', 'yes' );
				}
			}


			public function ma_el_require_constants(){

				// Master Addons Text Domain
				if ( ! defined( 'MA_CF7_TD' ) ) {
					define( 'MA_CF7_TD', $this->ma_el_require_load_textdomain() );
				}
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
