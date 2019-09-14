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
	* Initializes the function by registering the save function with the
	* admin_post hook so that we can save our options to the database.
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
				$data = array(
				  'format'     => 'jsonv2',
				);
				$url = 'https://nominatim.openstreetmap.org/search.php?q=' . str_replace( ' ', '%20', sanitize_text_field( $_POST['title'] ) ) . '&' . http_build_query($data);
				$ch = curl_init( $url );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch, CURLOPT_USERAGENT, 'Dark Secret Ninja/1.0' );
				$geopos = curl_exec( $ch );
				curl_close( $ch );
				$json_data = json_decode( $geopos, true );
				$lat = array( $json_data[0]['lat'], $json_data[0]['lon'] );
				echo json_encode( $lat );
				die();
			}
		}
		add_action( 'wp_ajax_vswpmm_ajax_map', 'vswpmm_ajax_map' );
		add_action( 'wp_ajax_nopriv_vswpmm_ajax_map', 'vswpmm_ajax_map' );
    }
}

?>