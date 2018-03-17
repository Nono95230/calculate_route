(function($, Drupal, drupalSettings){

	'use strict';

	// Properties for api key
	var akv 				= drupalSettings.calculate_route.JS.api_key_is_valid,
		CR_AK_IS_VALID 		= (akv == 1 ? true :false);



	//////////////////////////////////////////////////
	///                                            ///
	///            FUNCTION APIKEYISVALID          ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////


	Drupal.behaviors.apiKeyIsValid = {
		attach : function(context, settings) {

			$(document).ready( function(){

				if (CR_AK_IS_VALID) {
					$(".form-item-api-key").addClass("is-valid");
					$("input#edit-api-key").after("<span id='success'><i class='fa fa-check fa-lg'></i></span>");
				}
				else{
					$(".toolbar-icon-calculate-route-config-apikey").parent("li").removeClass("menu-item--expanded").find("ul").remove();
					$(".form-item-api-key").addClass("no-valid");
					$("input#edit-api-key").after("<span id='error'><i class='fa fa-close fa-lg'></i></span>");
				}

			});
			
		}
	};




	//////////////////////////////////////////////////
	///                                            ///
	///            FUNCTION APIKEYISVALID          ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////



	//////////////////////////////////////////////////
	///                                            ///
	///     FUNCTION AUTOCOMPLETE FIELD ADDRESS    ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.autocompleteAddressField = {
		attach : function(context, settings) {

			$(document).ready( function(){


				/*
				 * Generate the AutoComplete Fields
				 * Start
				 * 
				 */
				
				var findAddressAC 	= $("#edit-map-center").find("#edit-address").attr('id'),
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
				
				 $('#edit-address').on("change", function(){

					setTimeout(function(elementThis){ 

				 		var address = elementThis.val(); 
						console.log(address);
						//@todo finish the map refresh when autocomplete address change
						initMapAfterAddressAutocomplete(address);
					}, 100,$(this));
				 });


				/* 
				 * Save the Autocomplete Value
				 * End
				 * 
				 */

			});
			
			function initMapAfterAddressAutocomplete(addressValue){

				var address = addressValue;

				var mps = drupalSettings.calculate_route.JS.map_settings,
					// CR_LATITUDE  		= lat,
					// CR_LONGITUDE 		= lng,
					CR_ZOOM 	 		= Number(mps.zoom),
					CR_MAP_TYPE  		= mps.map_type,
					CR_ZOOM_MAX 		= Number(mps.zoom_max),
					CR_ZOOM_SCROLL 		= (mps.zoom_scroll == "true"),
					CR_ENABLE_GEOLOC 	= (mps.enable_geoloc == "true"),
					mks = drupalSettings.calculate_route.JS.marker_settings,
					CR_LATITUDE_MK 		= Number(mks.latitude),
					CR_LONGITUDE_MK 	= Number(mks.longitude),
					CR_TITLE_MK 		= mks.title,
					CR_ENABLE_IW_MK 	= (mks.enable_info_window == "true"),
					CR_INFO_WINDOW 		= mks.info_window;
				/*
				 * Generate the Map
				 * Start
				 * 
				 */
				 
				var map = new google.maps.Map(document.getElementById('map-cr'), {
					// center: {lat: CR_LATITUDE, lng: CR_LONGITUDE},
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
				 * Generate the AutoComplete Fields
				 * Start
				 * 
				 */

				var findAddressAC 	= $("#container_form").find("input.autocomplete-place").attr('id'),
					addressAC		= document.getElementById(findAddressAC),
		        	AutoComplete 	= new google.maps.places.Autocomplete(addressAC, { types: ['geocode'] } );

				/*
				 * Generate the AutoComplete Fields
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
					
			}
			
		}
	};


	//////////////////////////////////////////////////
	///                                            ///
	///     FUNCTION AUTOCOMPLETE FIELD ADDRESS    ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////

	//////////////////////////////////////////////////
	///                                            ///
	///     FUNCTION AUTOCOMPLETE FIELD ADDRESS    ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////



	//////////////////////////////////////////////////
	///                                            ///
	///          FUNCTION INITMAP REFRESH          ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////


	Drupal.behaviors.initMapRefresh = {
		attach : function(context, settings) {
			

		}
	};



	function handleLocationError(browserHasGeolocation, infoWindow, pos) {
		infoWindow.setPosition(pos);
		infoWindow.setContent(browserHasGeolocation ?
		'Error: The Geolocation service failed.' :
		'Error: Your browser doesn\'t support geolocation.');
	}

	//////////////////////////////////////////////////
	///                                            ///
	///          FUNCTION INITMAP REFRESH          ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////

})(jQuery, Drupal, drupalSettings);



