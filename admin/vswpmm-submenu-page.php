<?php
/**
 * Creates the submenu page for the plugin.
 *
 * Provides the functionality necessary for rendering the page corresponding
 * to the submenu with which this page is associated.
 *
 * @package very-simple-wp-maker-map
 */
class VSWPMM_Submenu_Page {
	/**
	* This function renders the contents of the page associated with the Submenu
	* that invokes the render method. In the context of this plugin, this is the
	* Submenu class.
	*/
	private $deserializer_vswpmm;
	public function __construct( $deserializer_vswpmm ) {
		$this->deserializer_vswpmm = $deserializer_vswpmm;
	}
	
    public function render() {
        include_once( 'views/vswpmm-settings.php' );
    }
}