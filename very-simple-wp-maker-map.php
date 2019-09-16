<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/jschaves/
 * @since             1.0
 * @package           very-simple-wp-maker-map
 *
 * @wordpress-plugin
 * Plugin Name:       Very Simple WP Maker Map
 * Plugin URI:        https://github.com/jschaves/very-simple-wp-maker-map
 * Description:       Create map in the selected direction with a marker with text and link.
 * Version:           1.0
 * Author:            Juan Chaves
 * Author URI:        https://github.com/jschaves/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       very-simple-wp-maker-map
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if( !defined( 'WPINC' ) ) {
    exit();
}
// Include the shared dependency.
include_once( plugin_dir_path( __FILE__ ) . 'shared/vswpmm-class-deserializer.php' );
// Include the dependencies needed to instantiate the plugin.
foreach( glob( plugin_dir_path( __FILE__ ) . 'admin/*.php' ) as $file ) {
    include_once $file;
}
add_action('plugins_loaded', 'very_simple_wp_maker_map_menu');
// Update CSS within in Admin
function vswpmm_admin_style() {
	wp_enqueue_style( 'vswpmm-admin-styles', plugin_dir_url( __FILE__ ) . 'admin/css/style.css' );
}
add_action('admin_enqueue_scripts', 'vswpmm_admin_style');
// Include the shared and public dependencies.
include_once( plugin_dir_path( __FILE__ ) . 'shared/vswpmm-class-deserializer.php' );
include_once( plugin_dir_path( __FILE__ ) . 'public/vswpmm-class-content-messenger.php' );
//add languages 
function vswpmm_add_languages() {
	load_plugin_textdomain( 'very-simple-wp-maker-map', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
// add library openlayers, public and admin dependencies
add_action( 'plugins_loaded', 'vswpmm_add_languages' );
function vswpmm_add_js_openlayers() {
	wp_enqueue_script( 'vswpmm-add-js-openlayers',  plugin_dir_url( __FILE__ ) . 'shared/lib/ol.js', false );
	wp_enqueue_style( 'vswpmm-add-css-openlayers',  plugin_dir_url( __FILE__ ) . 'shared/lib/ol.css', false );
	wp_register_style( 'vswmm-maker-map-css', plugin_dir_url( __FILE__ ) . 'public/css/style.css' );
	wp_enqueue_style( 'vswmm-maker-map-css' );
}
add_action( 'admin_enqueue_scripts', 'vswpmm_add_js_openlayers');
add_action( 'wp_enqueue_scripts', 'vswpmm_add_js_openlayers');
//add link to text map
function vswpmm_custom_wpkses_post_tags( $tags, $context ) {
	if ( 'post' === $context ) {
		$tags['a'] = array(
			'href'             => true,
			'height'          => true,
			'width'           => true,
			'title'     => true,
			'alt' => true,
			'target' => true,
		);
	}
	return $tags;
}
add_filter( 'wp_kses_allowed_html', 'vswpmm_custom_wpkses_post_tags', 10, 2 );
/**
 * Starts the plugin.
 *
 */
function very_simple_wp_maker_map_menu() {
	// Setup and initialize the class for saving our options.
    $serializer_vswpmm = new VSWPMM_Serializer();
    $serializer_vswpmm->init();
	// Setup the class used to retrieve our option value.
	$deserializer_vswpmm = new VSWPMM_Deserializer();
	// Setup the administrative functionality.
    $plugin_vswpmm = new VSWPMM_Submenu( new VSWPMM_Submenu_Page( $deserializer_vswpmm ) );
    $plugin_vswpmm->init();
	// Setup the public facing functionality.
    $public_vswpmm = new VSWPMM_Content_Messenger( $deserializer_vswpmm );
    $public_vswpmm->init();
	// Setup and initialize the class for map our options.
    $maps_vswpmm = new VSWPMM_Map();
    $maps_vswpmm->init();
}