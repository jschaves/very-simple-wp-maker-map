"use strict";
var vswpmmIdMap;
var vswpmmWidthMap;
var vswpmmHeightMap;
(function($) {
	jQuery(document).ready(function() {
		//copy code shortcodes
		jQuery('.vswpmm-copy').on('click', function() {
			var aux = document.createElement('input');
			jQuery(aux).val(jQuery(this).attr('copy'));
			document.body.appendChild(aux);
			aux.select();
			document.execCommand('copy');
			document.body.removeChild(aux);
		});
		//edit
		jQuery('.vswpmm-view-input').click(function() {
			vswpmmIdMap = jQuery(
				'#' + jQuery(this).attr('vswpmmId')
			).attr(
				'viewmakerMap'
			).split(
				'vswpmakermap '
			)[1].split(',');
			jQuery('#vswpmm-id-maker-map-edit').val(vswpmmIdMap[0].split('ID=')[1]);
			jQuery('#vswpmm-address').val(vswpmmIdMap[1].split('address=')[1]);
			jQuery('#vswpmm-width-maker-map').val(vswpmmIdMap[2].split('width=')[1]);
			jQuery('#vswpmm-height-maker-map').val(vswpmmIdMap[3].split('height=')[1]);
			jQuery('#vswpmm-maker-title').val(vswpmmIdMap[4].split('title=')[1]);
			jQuery('#vswpmm-maker-text-color').val(vswpmmIdMap[5].split('color=')[1]);
			jQuery('#vswpmm-background-text-color').val(vswpmmIdMap[6].split('background=')[1]);
			jQuery('#vswpmm-maker-text').val(decodeURIComponent(escape(window.atob(vswpmmIdMap[7].split('text=')[1]))));
			jQuery('#vswpmm-lon-lat-maker-map-save').val(vswpmmIdMap[8].split('LonLat=')[1]);
			vswpmmPost('view');
		});
		//prewiew
		jQuery("#vswpmm-link-data-maker-map").click( function() {
			vswpmmPost('view');			
		});
		//post lon lat
		function vswpmmPost(id) {
			jQuery.ajax({
				type : 'post',
				dataType : 'json',
				url : vswpmm_vars.ajaxurl,
				data : {
					action: 'vswpmm_ajax_map',
					_ajax_nonce:  vswpmm_vars.nonce,
					'title': $('#vswpmm-address').val()
				},
				success: function(response) {
					if(!response) {
						console.log('Error');
					} else {
						if(id == 'view') {
							vswpmmHtml(response);
						} else {
							jQuery('#vswpmm-lon-lat-maker-map-save').val(response[1] + 'vswpmm' + response[0]);
						}
					}
				}
			});
			if(id === 'view') {
				jQuery('#vswpmm-paint').css(
					{
						'width': jQuery('#vswpmm-width-maker-map').val() + '%',
						'height': jQuery('#vswpmm-height-maker-map').val() + 'px'
					}
				).fadeIn();
			}
		}
		//html
		function vswpmmHtml(response) {
			vswpmmWidthMap = jQuery('#vswpmm-width-maker-map').val() + '%';
			vswpmmHeightMap = jQuery('#vswpmm-height-maker-map').val() + 'px';
			jQuery('#vswpmm-js-map-new, #vswpmm-paint').html('');
			jQuery('#vswpmm-js-map-new').html(
				'<style type="text/css">' +
				'.ol-popup:after {' +
				'border-top-color:' + jQuery('#vswpmm-background-text-color').val() + ' !important;' +
				'border-width: 10px;' +
				'left: 48px;' +
				'margin-left: -10px;' +
				'}' +
				'</style>' +
				'<div id="vswpmm-popup" class="ol-popup" style="background-color:' + 
				jQuery('#vswpmm-background-text-color').val() +
				';color:' + jQuery('#vswpmm-maker-text-color').val() +
				'">' +
				'<a href="#" id="vswpmm-popup-closer" class="ol-popup-closer"></a>' +
				'<div id="vswpmm-popup-content"></div>' +
				'</div>' +
				'<script>' +
				'var attribution = new ol.control.Attribution({' +
				'collapsible: false' +
				'});' +
				'var map = new ol.Map({' +
				'controls: ol.control.defaults({ attribution: false }).extend([attribution]),' +
				'layers: [' +
				'new ol.layer.Tile({' +
				'source: new ol.source.OSM({' +
				'url: \'https://tile.openstreetmap.org/{z}/{x}/{y}.png\',' +
				'attributions: [ol.source.OSM.ATTRIBUTION, \'Tiles courtesy of <a href="https://geo6.be/">GEO-6</a>\'],' +
				'maxZoom: 18' +
				'})' +
				'})' +
				'],' +
				'target: \'vswpmm-paint\',' +
				'view: new ol.View({' +
				'center: ol.proj.fromLonLat([' + response[1] + ', ' + response[0] + ']),' +
				'maxZoom: 18,' +
				'zoom: 14' +
				'})' +
				'});' +
				'var layer = new ol.layer.Vector({' +
				'source: new ol.source.Vector({' +
				'features: [' +
				'new ol.Feature({' +
				'geometry: new ol.geom.Point(ol.proj.fromLonLat([' + response[1] + ', ' + response[0] + ']))' +
				'})' +
				']' +
				'})' +
				'});' +
				'map.addLayer(layer);' +
				'var container = document.getElementById(\'vswpmm-popup\');' +
				'var content = document.getElementById(\'vswpmm-popup-content\');' +
				'var closer = document.getElementById(\'vswpmm-popup-closer\');' +
				'var overlay = new ol.Overlay({' +
				'element: container,' +
				'autoPan: true,' +
				'autoPanAnimation: {' +
				'duration: 250' +
				'}' +
				'});' +
				'map.addOverlay(overlay);' +
				'closer.onclick = function () {' +
				'overlay.setPosition(undefined);' +
				'closer.blur();' +
				'return false;' +
				'};' +
				'map.on(\'singleclick\', function (event) {' +
				'if (map.hasFeatureAtPixel(event.pixel) === true) {' +
				'var coordinate = event.coordinate;' +
				'content.innerHTML = \'<b>' + jQuery('#vswpmm-maker-title').val() + '</b><br />' + jQuery('#vswpmm-maker-text').val() + '\';' +
				'overlay.setPosition(coordinate);' +
				'} else {' +
				'overlay.setPosition(undefined);' +
				'closer.blur();' +
				'}' +
				'});' +
				'content.innerHTML = \'<b>' + jQuery('#vswpmm-maker-title').val() + '</b><br />' + jQuery('#vswpmm-maker-text').val() + '\';' +
				'overlay.setPosition(ol.proj.fromLonLat([' + response[1] + ', ' + response[0] + ']));' +
				'</script>'
			);			
		}
		//on submit map
		jQuery('#vswpmm-address').on('change', function() {
			console.log(1);
			vswpmmPost('change');
		});
		//on reset
		jQuery('#vswpmm-reset').on('click', function() {
			location.reload(true);
		});
	});
})(jQuery);