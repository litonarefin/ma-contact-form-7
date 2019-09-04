<?php
<<<<<<< HEAD
=======
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 9/4/19
	 */
>>>>>>> 9e1e7a08c2c96349592e60986e11ec799a7b1a27

if ( !function_exists( 'ma_el_cf7_retrive_form' ) ) {
	function ma_el_cf7_retrive_form() {
		if ( function_exists( 'wpcf7' ) ) {
			$wpcf7_form_list = get_posts(array(
				'post_type' => 'wpcf7_contact_form',
				'showposts' => 999,
			));
			$options = array();
			$options[0] = esc_html__( 'Select a Form', MA_CF7_TD );
			if ( ! empty( $wpcf7_form_list ) && ! is_wp_error( $wpcf7_form_list ) ){
				foreach ( $wpcf7_form_list as $post ) {
					$options[ $post->ID ] = $post->post_title;
				}
			} else {
				$options[0] = esc_html__( 'Create a Form First', MA_CF7_TD );
			}
			return $options;
		}
	}
}