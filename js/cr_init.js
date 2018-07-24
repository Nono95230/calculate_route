(function($, Drupal, drupalSettings){

	'use strict';

	// Google Maps Property
	var map,
		marker,
		directionsDisplay,
		directionsService,
		transitLayer,//Propriété pour le trafic autoroutier
		trafficLayer;//Propriété pour le trafic de transport public de personne

	// Properties for api key
	var apiKeyState 		= drupalSettings.calculate_route.JS.api_key_is_valid,
		CR_AK_IS_VALID 		= (apiKeyState == 1 ? true :false);

	// Properties for map
	var mps 				= drupalSettings.calculate_route.JS.map_settings,
		CR_MAP_TYPE 		= mps.map_type,
		CR_LATITUDE 		= Number(mps.latitude),
		CR_LONGITUDE 		= Number(mps.longitude),
		CR_ZOOM 			= Number(mps.zoom),
		CR_ZOOM_MAX 		= Number(mps.zoom_max),
		CR_ZOOM_SCROLL 		= (mps.zoom_scroll == 1),
		CR_ENABLE_GEOLOC	= (mps.enable_geoloc == 1);

	// Properties for marker
	var mks 			= drupalSettings.calculate_route.JS.marker_settings,
		CR_LATITUDE_MK 	= Number(mks.latitude),
		CR_LONGITUDE_MK = Number(mks.longitude),
		CR_TITLE_MK 	= mks.title,
		CR_ENABLE_IW_MK = (mks.enable_info_window == 1),
		CR_INFO_WINDOW 	= mks.info_window;

	// Property for form
	var fms 				= drupalSettings.calculate_route.JS.form_settings,
		CR_TRANSPORT 		= fms.transport,
		CR_EN_SWITCH 		= fms.btn_switch,
		CR_EN_MINI_REST_BTN = fms.btn_minimize_restore,
		CR_LABEL_START 		= fms.sl_start,
		CR_LABEL_END 		= fms.sl_end;

	// Property for appearence
	var aps 				= drupalSettings.calculate_route.JS.appearence_settings,
		CR_WIDTH_MAP 		= aps.width_map,
		CR_HEIGHT_MAP 		= aps.height_map,
		CR_LABEL_TEXT_COLOR = "#"+aps.label_text_color,
		CR_BTN_TEXT_COLOR 	= "#"+aps.button_text_color,
		CR_HEADER_BG_COLOR 	= "#"+aps.header_color,
		CR_FORM_BG_COLOR 	= "#"+aps.form_color,
		CR_BTN_BG_COLOR 	= "#"+aps.button_color,
		CR_3_BTN_COLOR 		= "#"+aps.three_btn_color,
		CR_3_BTN_HV_COLOR 	= "#"+aps.three_btn_hover_color,
		CR_TOP_POS 			= aps.top_position,
		CR_BOTTOM_POS 		= aps.bottom_position,
		CR_LEFT_POS 		= aps.left_position,
		CR_RIGHT_POS 		= aps.right_position;


	//////////////////////////////////////////////////
	///                                            ///
	///           FUNCTION INITAPPEARENCE          ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////


	Drupal.behaviors.initGeneral = {
		attach : function(context, settings) {

			$(document).ready( function(){

				if (!CR_AK_IS_VALID) {
					$("#block-calculaterouteblock").addClass("api-key-false");
					$(".toolbar-icon-calculate-route-config-apikey").parent("li").removeClass("menu-item--expanded").find("ul").remove();
				}

				/*
				 * CHANGE MAP DIMENSION
				 */

				$('#container_map').css({
					'width' : CR_WIDTH_MAP,
					'height' : CR_HEIGHT_MAP
				});
				
				// Only for Admin Page
				if (window.location.href.includes('calculate-route/config/')) {
					$('#block-calculate-your-route').css({
						'width' : CR_WIDTH_MAP,
						'height' : CR_HEIGHT_MAP
					});
				}
				
				/*
				 * CHANGE MAP DIMENSION
				 */

				/*
				 * CHANGE FORM POSITION
				 */
				$('#container_form').css({
					'background-color' : CR_FORM_BG_COLOR,
					'top' : CR_TOP_POS,
					'bottom' : CR_BOTTOM_POS,
					'left' : CR_LEFT_POS,
					'right' : CR_RIGHT_POS
				});
				/*
				 * CHANGE FORM POSITION
				 */

				/*
				 * CHANGE FORM COLOR
				 */
				$('.label_style').css('color', CR_LABEL_TEXT_COLOR);

				$('#label_btn').css('color', CR_BTN_TEXT_COLOR);

				$('#choice_mode').css('background-color', CR_HEADER_BG_COLOR);

				$('#label_btn').css('background-color', CR_BTN_BG_COLOR);

					/* START - BTN SWITCH */
					$('#switch span').css('color', CR_3_BTN_COLOR);

					$("#switch span").on("mouseover", function() {
					  $(this).css('color', CR_3_BTN_HV_COLOR);
					});

					$("#switch span").on("mouseout", function() {
					  $(this).css('color', CR_3_BTN_COLOR);
					});
					/* END - BTN SWITCH */

					/* START - BTN MINIMIZE/RESTORE FORM */
					$('#minimize_restore span,#form-restore span').css('color', CR_3_BTN_COLOR);

					$("#minimize_restore span,#form-restore span").on("mouseover", function() {
					  $(this).css('color', CR_3_BTN_HV_COLOR);
					});

					$("#minimize_restore span,#form-restore span").on("mouseout", function() {
					  $(this).css('color', CR_3_BTN_COLOR);
					});
					/* END - BTN MINIMIZE/RESTORE FORM */

				/*
				 * CHANGE FORM COLOR
				 */

				/*
				 * START - ENABLE/DISABLE SWITCH BUTTON
				 */
				if (CR_EN_SWITCH == 0) {
					$("#switch").addClass('hidden');
				}
				/*
				 * END - ENABLE/DISABLE SWITCH BUTTON
				 */
				/*
				 * START - ENABLE/DISABLE FORM MINIMIZE BUTTON
				 */
				if (CR_EN_MINI_REST_BTN == 0) {
					$("#minimize_restore").addClass('hidden');
				}
				/*
				 * END - ENABLE/DISABLE FORM MINIMIZE BUTTON
				 */

				/*
				 * START - ENABLE/DISABLE LABEL
				 */
				if (CR_LABEL_START == 0) {
					$('#label_start').addClass('hidden');
				}

				if (CR_LABEL_END == 0 ) {
					$('#label_end').addClass('hidden');
				}
				/*
				 * END - ENABLE/DISABLE LABEL
				 */

				/*
				 * START - ENABLE/DISABLE MANY TRANSPORT WAY
				 */
				if (CR_TRANSPORT.car == 0) {
					$('#car-logo').addClass('hidden');
				}

				if (CR_TRANSPORT.public_transport == 0) {
					$('#public_transport-logo').addClass('hidden');
				}

				if (CR_TRANSPORT.bike == 0) {
					$('#bike-logo').addClass('hidden');
				}

				if (CR_TRANSPORT.walker == 0) {
					$('#walker-logo').addClass('hidden');
				}
				/*
				 * END - ENABLE/DISABLE MANY TRANSPORT WAY
				 */



			});

		}
	};


	//////////////////////////////////////////////////
	///                                            ///
	///           FUNCTION INITAPPEARENCE          ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////



	//////////////////////////////////////////////////
	///                                            ///
	///              FUNCTION INITMAP              ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////


	Drupal.behaviors.initMap = {
		attach : function(context, settings) {

			$(document).ready( function(){

				/*
				 * Generate the Map
				 * Start
				 *
				 */

				map = new google.maps.Map(document.getElementById('map-cr'), {
					center: {lat: CR_LATITUDE, lng: CR_LONGITUDE},
					zoom: CR_ZOOM,
					mapTypeId: CR_MAP_TYPE,
					maxZoom: CR_ZOOM_MAX,
					scrollwheel: CR_ZOOM_SCROLL
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
		        marker = new google.maps.Marker({
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








			});

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
	///              FUNCTION INITMAP              ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////


	//////////////////////////////////////////////////
	///                                            ///
	///         FUNCTION CLICK CHOICE MODE         ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.clickChoiceMode = {
		attach : function(context, settings) {

			$(document).ready( function(){

				$("#choice_mode span").on("click", function(){

					var tranportType 	= $(this).attr("id"),
						$actualActive 	= $("#choice_mode span.active");

					if (tranportType !== $actualActive.attr("id")) {
						$actualActive.removeClass('active');
						$(this).addClass('active');
					}

				});
			});

		}
	};

	//////////////////////////////////////////////////
	///                                            ///
	///         FUNCTION CLICK CHOICE MODE         ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////


	//////////////////////////////////////////////////
	///                                            ///
	///           FUNCTION CLICK SWITCH            ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.clickSwitch = {
		attach : function(context, settings) {

			$(document).ready( function(){

				$("#switch span").on("click", function(event){

					event.preventDefault();

					var containerStart 	= $("#sl_start"),
						containerEnd 	= $("#sl_end"),
						$start 			= containerStart.find("#start"),
						$end 			= containerEnd.find("#end");

					$("#sl_start #start").remove();
					$("#sl_end #end").remove();
					containerStart.append($end);
					containerEnd.append($start);
					containerStart.find('#end').attr('name','start').attr('id','start');
					containerEnd.find('#start').attr('name','end').attr('id','end');

				});
			});

		}
	};

	//////////////////////////////////////////////////
	///                                            ///
	///           FUNCTION CLICK SWITCH            ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////


	//////////////////////////////////////////////////
	///                                            ///
	///       FUNCTION CLICK MINIMIZE FORM         ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.clickMinimizeForm = {
		attach : function(context, settings) {

			$(document).ready( function(){

				if (CR_EN_MINI_REST_BTN == 1) {

					$("#minimize_restore span").on("click", function(){
						$("#container_form").css("overflow","hidden");
				    	$("#container_form").animate({width:"65px", height:"30px" }, 750);
					 	setTimeout(function(){
					 		$("#choice_mode #walker-logo").css("display","none");
					 	}, 150);
					 	setTimeout(function(){
					 		$("#choice_mode #bike-logo").css("display","none");
					 	}, 300);
				 		if ( CR_EN_SWITCH == 1 ) {
						 	setTimeout(function(){
						 		$("#switch").css("display","none");
						 	}, 300);
				 		}
					 	setTimeout(function(){
					 		$("#choice_mode #public_transport-logo").css("display","none");
					 	}, 450);
					 	setTimeout(function(){
					 		$("#choice_mode #car-logo").css("display","none");
					 		$("#choice_mode").css("display","none");
					 		$("#container_form").css("padding","5px");
					 	}, 500);
					 	setTimeout(function(){
					 		$("#form-restore span").css("display","block");
					 		$("#sl_start,#sl_end,#label_btn,#minimize_restore span").css("display","none");
					 	}, 650);

					});

				}

			});

		}
	};

	//////////////////////////////////////////////////
	///                                            ///
	///       FUNCTION CLICK MINIMIZE FORM         ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////


	//////////////////////////////////////////////////
	///                                            ///
	///        FUNCTION CLICK RESTORE FORM         ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.clickRestoreForm = {
		attach : function(context, settings) {

			$(document).ready( function(){

				if (CR_EN_MINI_REST_BTN == 1) {

					$("#form-restore span").on("click", function(){
				    	$("#container_form").animate({width:"280px", height:"311px" }, 750);

					 	setTimeout(function(){
					 		$("#choice_mode").css("display","block");
				 			$("#form-restore span").css("display","none");
				 			$("#sl_start,#sl_end,#minimize_restore span").css("display","block");
					 		$("#container_form").css("padding","60px 30px 20px");
					 		$("#container_form").css("height","auto");
					 		$("#label_btn").css("display","inline-block");
					 	}, 150);
					 	setTimeout(function(){
					 		$("#choice_mode #car-logo").css("display","inline-block");
					 	}, 200);
					 	setTimeout(function(){
					 		$("#choice_mode #public_transport-logo").css("display","inline-block");
					 		if ( CR_EN_SWITCH == 1 ) {
				 				$("#switch").css("display","block");
					 		}
					 	}, 400);
					 	setTimeout(function(){
					 		$("#choice_mode #bike-logo").css("display","inline-block");
					 	}, 550);
					 	setTimeout(function(){
					 		$("#choice_mode #walker-logo").css("display","inline-block");
					 	}, 700);
					 	setTimeout(function(){
						$("#container_form").css("overflow","initial");
					 	}, 800);

					});

				}

			});

		}
	};

	//////////////////////////////////////////////////
	///                                            ///
	///        FUNCTION CLICK RESTORE FORM         ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////


	//////////////////////////////////////////////////
	///                                            ///
	///             FUNCTION FINDROUTE             ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.findRoute = {
		attach : function(context, settings) {

			$(document).ready( function(){

				$("#label_btn").on("click", function(){

					var addressStart 	= $("#start").val(),
						addressEnd 		= $("#end").val(),
						travelMode 		= $("#choice_mode span.active").data('transport'),
						panel 			= document.getElementById('directions-panel');

			        // Create a renderer for directions and bind it to the map.
			        if (typeof directionsDisplay !== 'undefined') {

						if( directionsDisplay != null ) {
						    directionsDisplay.setMap(null);
						    directionsDisplay.setPanel(null);
            				directionsDisplay.setRouteIndex(null);
						    directionsDisplay = null;

					        transitLayer.setMap(null);
					        transitLayer = null;

					        trafficLayer.setMap(null);
					        trafficLayer = null;

				        	directionsService = null;
						}
					}

			        if (marker !== null) {
						marker.setMap(null);
						marker = null;
					}

			        directionsDisplay = new google.maps.DirectionsRenderer({map: map, panel: panel});
			        directionsService = new google.maps.DirectionsService;
					transitLayer = new google.maps.TransitLayer();
					trafficLayer = new google.maps.TrafficLayer();

			        if (travelMode === "DRIVING") {
						transitLayer.setMap(map);
			        }
			        if (travelMode === "TRANSIT") {
		  				trafficLayer.setMap(map);
			        }


			        calculateAndDisplayRoute(directionsDisplay, directionsService, map, addressStart, addressEnd, travelMode, transitLayer, trafficLayer);


				});

			});

		}
	};
	//////////////////////////////////////////////////
	///                                            ///
	///             FUNCTION FINDROUTE             ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////

	function calculateAndDisplayRoute(directionsDisplay, directionsService, map, addressStart, addressEnd, travelMode, transitLayer, trafficLayer){
		var currentTime = Date.now();
		$('#overlay').addClass('show-overlay');

		// Retrieve the start and end locations and create a DirectionsRequest
		directionsService.route({
			origin: addressStart,
			destination: addressEnd,
			provideRouteAlternatives: true,
			travelMode: travelMode,
			unitSystem: google.maps.UnitSystem.METRIC
		}, function(response, status) {
			// Route the directions and pass the response to a function to create
			// markers for each step.
			if (status === 'OK') {
				for (var i = 0, len = response.routes.length; i < len; i++) {
					new google.maps.DirectionsRenderer({
						directions: response,
						routeIndex: i
					});
				}


				var nb_route = response.routes.length;

				directionsDisplay.setRouteIndex(nb_route);

				directionsDisplay.setDirections(response);

		    	setTimeout(function(){
		    		$('#overlay').removeClass('show-overlay');

		    		/* START - If minimize button is enabled */
					if (CR_EN_MINI_REST_BTN == 1) {

						$("#container_form").css("overflow","hidden");
				    	$("#container_form").animate({width:"65px", height:"30px" }, 750);
					 	setTimeout(function(){
					 		$("#choice_mode #walker-logo").css("display","none");
					 	}, 150);
					 	setTimeout(function(){
					 		$("#choice_mode #bike-logo").css("display","none");
					 	}, 300);
				 		if ( CR_EN_SWITCH == 1 ) {
						 	setTimeout(function(){
						 		$("#switch").css("display","none");
						 	}, 300);
				 		}
					 	setTimeout(function(){
					 		$("#choice_mode #public_transport-logo").css("display","none");
					 	}, 450);
					 	setTimeout(function(){
					 		$("#choice_mode #car-logo").css("display","none");
					 		$("#choice_mode").css("display","none");
					 		$("#container_form").css("padding","5px");
					 	}, 500);
					 	setTimeout(function(){
					 		$("#form-restore span").css("display","block");
					 		$("#sl_start,#sl_end,#label_btn,#minimize_restore span").css("display","none");
					 	}, 650);

					}
		    		/* END - If minimize button is enabled */

		    	}, 750);


			} else {
				$('#overlay').removeClass('show-overlay');
				window.alert('Directions request failed due to ' + status);
			}
		});


	}




	/*Drupal.behaviors.functionName = {
		attach : function(context, settings) {
			$(document).ready( function(){
			});
		}
	};*/




})(jQuery, Drupal, drupalSettings);

