<?php
/**
 * Retrieves information from the database.
 *
 * This requires the information being retrieved from the database should be
 * specified by an incoming key. If no key is specified or a value is not found
 * then an empty string will be returned.
 *
 * @package very-simple-wp-maker-map
 */

class VSWPMM_Content_Messenger {
	/**
	 * A reference to the class for retrieving our option values.
	 *
	 * @access private
	 * @var    deserializer_vswpmm
	 */
	private $deserializer_vswpmm;
	/**
	 * Initializes the class by setting a reference to the incoming deserializer_vswpmm.
	 *
	 * @param deserializer_vswpmm $deserializer_vswpmm Retrieves a value from the database.
	 */
	public function __construct( $deserializer_vswpmm ) {
		$this->deserializer_vswpmm = $deserializer_vswpmm;
	}
    /**
     * Adds a submenu for this plugin to the 'Tools' menu.
     */
    public function init() {
        add_filter( 'the_content', array( $this, 'filterMakerMap' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'very_simple_wp_maker_map_public_scripts' ) );
    }
    /**
     * Get shortcodes an create javascript map
     */	
	public function filterMakerMap( $content ) {
		if( preg_match_all( '/\[vswpmakermap ID=.* address=.*\]/', $content, $ouputs, PREG_OFFSET_CAPTURE ) ) {
			for( $a = 0; $a < count( $ouputs[0] ); $a++ ) {
				$explodeId =  explode('ID=', $ouputs[0][$a][0]);
				$explodeId =  explode(' ', $explodeId[1]);
				$filter[$a] = $ouputs[0][$a][0];
				$values[$a] = esc_attr( $this->deserializer_vswpmm->get_filter( 'very_simple_wp_maker_map_' . $explodeId[0] ) );
			}
			for( $a = 0; $a < count( $values ); $a++ ) {
				if( !empty( $values[$a] ) ) { 
					$styleMakerMap = explode( ',', $values[$a] );
					$id = explode( '=', $styleMakerMap[0] );
					$address = explode( '=', $styleMakerMap[1] );
					$width = explode( '=', $styleMakerMap[2] );
					$height = explode( '=', $styleMakerMap[3] );
					$title = explode( '=', $styleMakerMap[4] );
					$color = explode( '=', $styleMakerMap[5] );
					$background = explode( '=', $styleMakerMap[6] );
					$text = explode( '=', $styleMakerMap[7] );
					$lonlat = explode( '=', $styleMakerMap[8] );
					$lonlat = str_replace( 'vswpmm', ',', $lonlat[1] );
					$html[$a] = '<style type="text/css">
								.ol-popup-' . $a . ' {
									font-size: 12px;
									position: absolute;
									background-color: ' . $background[1] . ';
									-webkit-filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
									filter: drop-shadow(0 1px 4px rgba(0, 0, 0, 0.2));
									padding: 15px;
									border-radius: 10px;
									border: 1px solid #cccccc;
									bottom: 12px;
									left: -50px;
									min-width: 100px;
								}
								.ol-popup-' . $a . ':after,
								.ol-popup-' . $a . ':before {
									top: 100%;
									border: solid transparent;
									content: " ";
									height: 0;
									width: 0;
									position: absolute;
									pointer-events: none;
								}
								.ol-popup-' . $a . ':after {
									border-top-color:' . $background[1] . ' !important;
									border-width: 10px;
									left: 48px;
									margin-left: -10px;
								}
								.ol-popup:-' . $a . 'before {
									border-top-color: #cccccc;
									border-width: 11px;
									left: 48px;
									margin-left: -11px;
								}
								.ol-popup-closer-' . $a . ' {
									text-decoration: none;
									position: absolute;
									top: 2px;
									right: 8px;
								}
								.ol-popup-closer-' . $a . ':after {
									content: "✖";
									color: #c3c3c3;
								}
							</style>
							<div id="vswpmm-paint-map-' . $a . '" style="width:' . $width[1] . '%; height:' . $height[1] . 'px;"></div>
							<div id="vswpmm-popup-' . $a . '" class="ol-popup-' . $a . '" style="background-color:' . $background[1] . ';color:' . $color[1] . '">
								<a href="#" id="vswpmm-popup-closer-' . $a . '" class="ol-popup-closer-' . $a . '"></a>
								<div id="vswpmm-popup-content-' . $a . '"></div>
							</div>
							<script>
								var attribution' . $a . ' = new ol.control.Attribution({
									collapsible: false
								});
								var map' . $a . ' = new ol.Map({
									controls: ol.control.defaults({ attribution: false }).extend([attribution' . $a . ']),
									layers: [
										new ol.layer.Tile({
											source: new ol.source.OSM({
												url: \'https://tile.openstreetmap.org/{z}/{x}/{y}.png\',
												attributions: [ol.source.OSM.ATTRIBUTION, \'Tiles courtesy of <a href="https://geo6.be/">GEO-6</a>\'],
												maxZoom: 18
											})
										})
									],
									target: \'vswpmm-paint-map-' . $a . '\',
									view: new ol.View({
										center: ol.proj.fromLonLat([' . $lonlat . ']),
										maxZoom: 18,
										zoom: 14
									})
								});
								var layer' . $a . ' = new ol.layer.Vector({
									source: new ol.source.Vector({
										features: [
											new ol.Feature({
												geometry: new ol.geom.Point(ol.proj.fromLonLat([' . $lonlat . ']))
											})
										]
									})
								});
								map' . $a . '.addLayer(layer' . $a . ');
								var container' . $a . ' = document.getElementById(\'vswpmm-popup-' . $a . '\');
								var content' . $a . ' = document.getElementById(\'vswpmm-popup-content-' . $a . '\');
								var closer' . $a . ' = document.getElementById(\'vswpmm-popup-closer-' . $a . '\');
								var overlay' . $a . ' = new ol.Overlay({
									element: container' . $a . ',
									autoPan: true,
									autoPanAnimation: {
										duration: 250
									}
								});
								map' . $a . '.addOverlay(overlay' . $a . ');
								closer' . $a . '.onclick = function () {
									overlay' . $a . '.setPosition(undefined);
									closer' . $a . '.blur();
									return false;
								};
								map' . $a . '.on(\'singleclick\', function (event) {
									if (map' . $a . '.hasFeatureAtPixel(event.pixel) === true) {
										var coordinate' . $a . ' = event.coordinate' . $a . ';
										content' . $a . '.innerHTML = \'<b>' . $title[1] . '</b><br />' . base64_decode( $text[1] ) . '\';
										overlay' . $a . '.setPosition(coordinate' . $a . ');
									} else {
										overlay' . $a . '.setPosition(undefined);
										closer' . $a . '.blur();
									}
								});
								content' . $a . '.innerHTML = \'<b>' . $title[1] . '</b><br />' . base64_decode( $text[1] ) . '\';
								overlay' . $a . '.setPosition(ol.proj.fromLonLat([' . $lonlat . ']));
							</script>';
					$content = str_replace( $filter[$a], $html[$a], $content );
				} else {
					$content = str_replace( $filter[$a], '', $content );
				}
			}
			return $content;
		} else {
			return $content;
		}
	}
}