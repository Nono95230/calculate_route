(function($, Drupal, drupalSettings){

	'use strict';


	//////////////////////////////////////////////////
	///                                            ///
	///            FUNCTION REFRESH MAP            ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.refreshMap = {
		attach : function(context, settings) {

			$(document).ready( function(){


				/*
				 * Generate the AutoComplete Fields
				 * Start
				 * 
				 */
				var inputAddress 	= $("#edit-map-center").find("#edit-address"),
					findAddressAC 	= inputAddress.attr('id'),
					addressAC		= document.getElementById(findAddressAC),
		        	AutoComplete 	= new google.maps.places.Autocomplete(addressAC, { types: ['geocode'] } );

				
				/*
				 * Generate the AutoComplete Fields
				 * End
				 * 
				 */


				/* 
				 * Save the Autocomplete Value
				 * Start
				 * 
				 */
				
				 $('#edit-address, #edit-zoom, #edit-zoom-max, #edit-zoom-scroll, #edit-map-type, #edit-enable-geoloc').on("change", function(){

					setTimeout(function(){ 


						var object = {
							address: $('#edit-address').val(),
							zoom: Number($('#edit-zoom').val()),
							zoom_max: Number($('#edit-zoom-max').val()),
							zoom_scroll: ($('#edit-zoom-scroll').val() == "true"),
							map_type: $('#edit-map-type').val(),
							en_geoloc: ($('#edit-enable-geoloc').val() == "true"),

						};

						console.log(object);
						refreshMap(object);
					}, 100,$(this));
				 });


				/* 
				 * Save the Autocomplete Value
				 * End
				 * 
				 */

			});

			
			function refreshMap(object){

				/*
				 * Set The Properties
				 * Start
				 * 
				 */
				
				var address 			= object.address,
					CR_ZOOM 	 		= object.zoom,
					CR_MAP_TYPE  		= object.map_type,
					CR_ZOOM_MAX 		= object.zoom_max,
					CR_ZOOM_SCROLL 		= object.zoom_scroll,
					CR_ENABLE_GEOLOC 	= object.en_geoloc,
					mks = drupalSettings.calculate_route.JS.marker_settings,
					CR_LATITUDE_MK 		= Number(mks.latitude),
					CR_LONGITUDE_MK 	= Number(mks.longitude),
					CR_TITLE_MK 		= mks.title,
					CR_ENABLE_IW_MK 	= (mks.enable_info_window == "true"),
					CR_INFO_WINDOW 		= mks.info_window;

				/*
				 * Set The Properties
				 * End
				 * 
				 */

				/*
				 * Generate the Map
				 * Start
				 * 
				 */
				 
				var map = new google.maps.Map(document.getElementById('map-cr'), {
					zoom: CR_ZOOM,
					mapTypeId: CR_MAP_TYPE,
					maxZoom: CR_ZOOM_MAX,
					scrollwheel: CR_ZOOM_SCROLL
				});
				
				var geocoder = new google.maps.Geocoder();

				geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						map.setCenter(results[0].geometry.location);
					} else {
						console.log("Geocode was not successful for the following reason: " + status);
					}
				});

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
		          position: {lat: CR_LATITUDE_MK, lng: CR_LONGITUDE_MK},
		          map: map,
		          title: CR_TITLE_MK
		        });

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

				if ( CR_ENABLE_IW_MK === true ) {

					var contentStringInfoWindow = CR_INFO_WINDOW;

					var infowindow = new google.maps.InfoWindow({
					content: contentStringInfoWindow
					});

				}

		        marker.addListener('click', function() {
					if ( CR_ENABLE_IW_MK === true ) {
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

				if ( CR_ENABLE_GEOLOC === true ) {

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
					

			}// End function refreshMap()
		}//End function attach
	};//End Bahaviors refreshMap


	//////////////////////////////////////////////////
	///                                            ///
	///            FUNCTION REFRESH MAP            ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////


})(jQuery, Drupal, drupalSettings);



