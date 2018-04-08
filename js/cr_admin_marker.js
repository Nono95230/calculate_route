(function($, Drupal, drupalSettings){

	'use strict';
	

	//////////////////////////////////////////////////
	///                                            ///
	///           FUNCTION REFRESH MARKER          ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.refreshMarker = {
		attach : function(context, settings) {

			$(document).ready( function(){


				/*
				 * Generate the AutoComplete Fields
				 * Start
				 * 
				 */
				var inputAddress 	= $("#edit-marker-position").find("#edit-address"),
					findAddressAC 	= inputAddress.attr('id'),
					addressAC		= document.getElementById(findAddressAC),
		        	AutoComplete 	= new google.maps.places.Autocomplete(addressAC, { types: ['geocode'] } );

				
				/*
				 * Generate the AutoComplete Fields
				 * End
				 * 
				 */


				/* 
				 * Event Change Marker Settings
				 * Start
				 * 
				 */
				var input = [
					'input[name="address_or_coordinate"]',
					'#edit-address',
					'#edit-latitude',
					'#edit-longitude',
					'#edit-title',
					'input[name="enable_info_window"]',
					'#edit-info-window-value'
				];

				$(input.join(',')).on("change", function(){

					setTimeout(function(){ 

						var condition 		= ($('#edit-reset-marker:checked').val() == 1),
							addr_or_coord 	= $('input[name="address_or_coordinate"]:checked').val(),
							mps 			= drupalSettings.calculate_route.JS.map_settings;

						var object = {
								map:{
									addr_or_coord: 	mps.address_or_coordinate,
									address: 		mps.address,
									latitude: 		Number(mps.latitude), 
									longitude: 		Number(mps.longitude),
									zoom: 			Number(mps.zoom),
									zoom_max: 		Number(mps.zoom_max),
									zoom_scroll: 	(mps.zoom_scroll == 1),
									map_type: 		mps.map_type,
									en_geoloc: 		(mps.enable_geoloc == 1)
								},
								marker:{
									addr_or_coord: 	addr_or_coord ,
									address: 		$('#edit-address').val(),
									latitude: 		Number($('#edit-latitude').val()),
									longitude: 		Number($('#edit-longitude').val()),
									title: 			$('#edit-title').val(),
									enable_iw: 		($('input[name="enable_info_window"]:checked').val() == 1),
									info_window: 	$('#edit-info-window-value').val()
								},
						};

						//console.log(object);
						refresh(object);
					}, 100,$(this));
				});


				/* 
				 * Event Change for Marker Settings
				 * End
				 * 
				 */

			});

			
			function refresh(object){

				/*
				 * Generate the Map
				 * Start
				 * 
				 */

				var map = new google.maps.Map(document.getElementById('map-cr'), {
					zoom: 			object.map.zoom,
					mapTypeId: 		object.map.map_type,
					maxZoom: 		object.map.zoom_max,
					scrollwheel: 	object.map.zoom_scroll
				});

				switch (object.map.addr_or_coord) {
				  case 'address':

					var geocoder = new google.maps.Geocoder();

					geocoder.geocode( { 'address': object.map.address}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							map.setCenter(results[0].geometry.location);
						} else {
							console.log("Geocode was not successful for the following reason: \n" + status);
						}
					});

				    break;

				  case 'coordinates':

					map.setCenter(new google.maps.LatLng(object.map.latitude, object.map.longitude));
					
				    break;
				}

				/*
				 * Generate the Map
				 * End
				 * 
				 */


				/*
				 * Generate the Marker
				 * Start
				 * 
				 */

		        var marker = new google.maps.Marker({
		          map: map,
		          title: object.marker.title
		        });

				switch (object.marker.addr_or_coord) {
				  case 'address':

					var geocoder = new google.maps.Geocoder();

					geocoder.geocode( { 'address': object.marker.address}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							marker.setPosition(results[0].geometry.location);
						} else {
							console.log("Geocode was not successful for the following reason: \n" + status);
						}
					});

				    break;

				  case 'coordinates':
					marker.setPosition(new google.maps.LatLng(object.marker.latitude, object.marker.longitude));
					
				    break;
				}

				/*
				 * Generate the Marker
				 * End
				 * 
				 */


				/*
				 * Generate the InfoWindowMarker
				 * Start
				 * 
				 */

				if ( object.marker.enable_iw === true ) {

					var contentStringInfoWindow = object.marker.info_window;

					var infowindow = new google.maps.InfoWindow({
					content: contentStringInfoWindow
					});

				}

		        marker.addListener('click', function() {
					if ( object.marker.enable_iw === true ) {
		          		infowindow.open(map, marker);
					}
		        });

				/*
				 * Generate the InfoWindowMarker
				 * End
				 * 
				 */


				/*
				 * Generate the Geolocation
				 * Start
				 * 
				 */

				if ( object.map.en_geoloc === true ) {

					var infoWindowGeo = new google.maps.InfoWindow({map: map});

					// Try HTML5 geolocation.
					if (navigator.geolocation) {
						navigator.geolocation.getCurrentPosition(function(position) {
							var pos = {
								lat: position.coords.latitude,
								lng: position.coords.longitude
							};

							infoWindowGeo.setPosition(pos);
							infoWindowGeo.setContent('Location found.');
							map.setCenter(pos);
						}, function() {
							handleLocationError(true, infoWindowGeo, map.getCenter());
						});
					} else {
						// Browser doesn't support Geolocation
						handleLocationError(false, infoWindowGeo, map.getCenter());
					}

				}

				/*
				 * Generate the Geolocation
				 * End
				 * 
				 */
				function handleLocationError(browserHasGeolocation, infoWindow, pos) {
					infoWindow.setPosition(pos);
					infoWindow.setContent(browserHasGeolocation ?
					'Error: The Geolocation service failed.' :
					'Error: Your browser doesn\'t support geolocation.');
				}
					

			}// End function refresh()
		}//End function attach
	};//End Bahaviors refreshMarker


	//////////////////////////////////////////////////
	///                                            ///
	///           FUNCTION REFRESH MARKER          ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////


})(jQuery, Drupal, drupalSettings);



