<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://github.com/jschaves/
 * @since      1.0
 * @package    very-simple-wp-maker-map
 */
// If uninstall not called from WordPress, then exit.
if ( ! defined('WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
// drop a custom database options values
function deleteOptionsMakerMap() {
	global $wpdb;
	$wpdb->query( "
		DELETE
		FROM
			`{$wpdb->options}`
		WHERE
			`option_name` LIKE 'very_simple_wp_makermap_%'
	" );
}
deleteOptionsMakerMap();