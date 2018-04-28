(function($, Drupal, drupalSettings){

	'use strict';

	// Properties for api key
	var akv 				= drupalSettings.calculate_route.JS.api_key_is_valid,
		CR_AK_IS_VALID 		= (akv == 1 ? true : false);


	//////////////////////////////////////////////////
	///                                            ///
	///            FUNCTION APIKEYISVALID          ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////


	Drupal.behaviors.apiKeyIsValid = {
		attach : function(context, settings) {

			$(document).ready( function(){
				var $formItem = $(".form-item-api-key");
				if (CR_AK_IS_VALID) {
					if ($('#gmapk-success').length === 0) {
						$formItem.addClass("is-valid");
						$formItem.find(".gm-api-key").after("<span id='gmapk-success'><i class='fa fa-check fa-lg'></i></span>");
					}
				}
				else{
					if ($('#gmapk-error').length === 0) {
						$formItem.addClass("no-valid");
						$formItem.find(".gm-api-key").after("<span id='gmapk-error'><i class='fa fa-close fa-lg'></i></span>");
					}
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



