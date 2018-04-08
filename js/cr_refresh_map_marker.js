(function($, Drupal, drupalSettings){

	'use strict';
	

	//////////////////////////////////////////////////
	///                                            ///
	///              FUNCTION REFRESH              ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.refresh = {
		attach : function(context, settings) {
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
	///              FUNCTION REFRESH              ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////


})(jQuery, Drupal, drupalSettings);



