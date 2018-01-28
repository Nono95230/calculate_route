(function($, Drupal, drupalSettings){


	'use strict';


	//////////////////////////////////////////////////
	///                                            ///
	///           FUNCTION INITAPPEARENCE          ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////


	Drupal.behaviors.initGeneral = {
		attach : function(context, settings) {

			$(document).ready( function(){

      			var aps 			= drupalSettings.calculate_route.JS.appearence_settings,
				CR_WIDTH_MAP 		= aps.width_map,
				CR_HEIGHT_MAP 		= aps.height_map,
				CR_LABEL_TEXT_COLOR = "#"+aps.label_text_color,
				CR_BTN_TEXT_COLOR 	= "#"+aps.button_text_color,
				CR_HEADER_BG_COLOR 	= "#"+aps.header_color,
				CR_FORM_BG_COLOR 	= "#"+aps.form_color,
				CR_BTN_BG_COLOR 	= "#"+aps.button_color,
				CR_SWITCH_COLOR 	= "#"+aps.switch_color,
				CR_SWITCH_HV_COLOR 	= "#"+aps.switch_hover_color,
				CR_TOP_POS 			= aps.top_position,
				CR_BOTTOM_POS 		= aps.bottom_position,
				CR_LEFT_POS 		= aps.left_position,
				CR_RIGHT_POS 		= aps.right_position;
				/*
				 * CHANGE MAP DIMENSION
				 */
				$('#map').css({
					'width' : CR_WIDTH_MAP,
					'height' : CR_HEIGHT_MAP
				});
				/*
				 * CHANGE MAP DIMENSION
				 */
				
				/*
				 * CHANGE FORM POSITION
				 */
				$('#container_options').css({
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
				$('#cr_submit').css('color', CR_BTN_TEXT_COLOR);
				$('#choice_mode').css('background-color', CR_HEADER_BG_COLOR);

				$('#cr_submit').css('background-color', CR_BTN_BG_COLOR);

				$('#switch span').css('color', CR_SWITCH_COLOR);
				$("#switch span").on("mouseover", function() {
				  $(this).css('color', CR_SWITCH_HV_COLOR);
				});
				$("#switch span").on("mouseout", function() {
				  $(this).css('color', CR_SWITCH_COLOR);
				});
				/*
				 * CHANGE FORM COLOR
				 */

      			var fms 			= drupalSettings.calculate_route.JS.form_settings,
				CR_TRANSPORT 		= fms.transport,
				CR_EN_SWITCH 		= fms.btn_switch,
				CR_LABEL_START 		= fms.sl_start,
				CR_LABEL_END 		= fms.sl_end;

				/*
				 * START - ENABLE BUTTON SWITCH
				 */
				if (CR_EN_SWITCH == 0) {
					$("#switch").css("display","none");
				}
				/*
				 * END - ENABLE BUTTON SWITCH
				 */

				/*
				 * START - ENABLE LABEL
				 */
				if (CR_LABEL_START == 1) {
					$('#label_start').css('visibility','hidden');
				}
				if (CR_LABEL_END == 1 ) {
					$('#label_end').css('visibility','hidden');
				}
				/*
				 * END - ENABLE LABEL
				 */

				/*
				 * START - ENABLE MANY TRANSPORT WAY
				 */
				if (CR_TRANSPORT.car == 0) {
					$('#car').css('display','none');
				}
				if (CR_TRANSPORT.public_transport == 0) {
					$('#public_transport').css('display','none');
				}
				if (CR_TRANSPORT.bike == 0) {
					$('#bike').css('display','none');
				}
				if (CR_TRANSPORT.walker == 0) {
					$('#walker').css('display','none');
				}
				/*
				 * END - ENABLE MANY TRANSPORT WAY
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
      			var mps 			= drupalSettings.calculate_route.JS.map_settings,
				CR_MAP_TYPE 		= mps.map_type,
				CR_LATITUDE 		= Number(mps.latitude),
				CR_LONGITUDE 		= Number(mps.longitude),
				CR_ZOOM 			= Number(mps.zoom),
				CR_ZOOM_MAX 		= Number(mps.zoom_max),
				CR_ZOOM_SCROLL 		= (mps.zoom_scroll == "true"),
				CR_ENABLE_GEOLOC	= (mps.enable_geoloc == "true");


				var map = new google.maps.Map(document.getElementById('map'), {
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
      			var mks 		= drupalSettings.calculate_route.JS.marker_settings,
				CR_LATITUDE_MK 	= Number(mks.latitude),
				CR_LONGITUDE_MK = Number(mks.longitude),
				CR_TITLE_MK 	= mks.title,
				CR_ENABLE_IW_MK = (mks.enable_info_window == "true"),
				CR_INFO_WINDOW 	= mks.info_window;

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
				
			});
			
		}
	};


	//////////////////////////////////////////////////
	///                                            ///
	///              FUNCTION INITMAP              ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////


	function handleLocationError(browserHasGeolocation, infoWindow, pos) {
		infoWindow.setPosition(pos);
		infoWindow.setContent(browserHasGeolocation ?
		'Error: The Geolocation service failed.' :
		'Error: Your browser doesn\'t support geolocation.');
	}


	//////////////////////////////////////////////////
	///                                            ///
	///             FUNCTION CLICKFORM             ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.clickForm = {
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

				$("#switch span").on("click", function(event){

					event.preventDefault();

					var containerStart 	= $("#adress-start"),
						containerEnd 	= $("#adress-end"),
						$start 			= containerStart.find("#start"),
						$end 			= containerEnd.find("#end");

					$("#adress-start #start").remove();
					$("#adress-end #end").remove();
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
	///             FUNCTION CLICKFORM             ///
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

				$("#cr_submit").on("click", function(){

					var adressStart 	= $("#start").val(),
						adressEnd 		= $("#end").val();
					console.log(adressStart);
					console.log(adressEnd);

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



	/*Drupal.behaviors.functionName = {
		attach : function(context, settings) {

			$(document).ready( function(){

			});

		}
	};*/
	
})(jQuery, Drupal, drupalSettings);



