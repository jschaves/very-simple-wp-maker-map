<?php
/**
 * Performs all sanitization functions required to save the option values to
 * the database.
 *
 * This will also check the specified nonce and verify that the current user has
 * permission to save the data.
 *
 * @package very-simple-wp-maker-map
 */
class VSWPMM_Map {
    /**
	* Initializes the function get lat lon map.
	*/
    public function init() {
		wp_register_script(
			'vswpmm_ajax_map',
			plugin_dir_url( __FILE__ ) . 'js/vswpmm-ajax-map.js',
			array('jquery')
		);
		wp_localize_script(
			'vswpmm_ajax_map',
			'vswpmm_vars',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'	=> wp_create_nonce( 'vsgpmm-map' )
			)
		);        
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'vswpmm_ajax_map' );

		function vswpmm_ajax_map() {
			check_ajax_referer( 'vsgpmm-map' );
			if( !empty($_POST['title'] ) ) { 				
				$q = sanitize_text_field( $_POST['title'] );
				$url = 'https://nominatim.openstreetmap.org/search.php?q=' . $q . '&format=jsonv2';
				$geopos = wp_remote_get( $url );
				$latlon = json_decode( $geopos['body'], true );
				echo json_encode( 
					array( $latlon[0]['lat'], 
						$latlon[0]['lon'] 
					) 
				) ;
				die();				
			}
		}
		add_action( 'wp_ajax_vswpmm_ajax_map', 'vswpmm_ajax_map' );
		add_action( 'wp_ajax_nopriv_vswpmm_ajax_map', 'vswpmm_ajax_map' );
    }
}

?>