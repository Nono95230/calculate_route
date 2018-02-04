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




})(jQuery, Drupal, drupalSettings);



