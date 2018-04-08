(function($, Drupal, drupalSettings){

	'use strict';
	

	//////////////////////////////////////////////////
	///                                            ///
	///           FUNCTION REFRESH FORM            ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.refreshForm = {
		attach : function(context, settings) {

			$(document).ready( function(){

				/*
				 * Generate the AutoComplete Fields
				 * Start
				 * 
				 */
				var inputAddress 	= $("#edit-address-destination").find("#edit-address"),
					findAddressAC 	= inputAddress.attr('id'),
					addressAC		= document.getElementById(findAddressAC),
		        	AutoComplete 	= new google.maps.places.Autocomplete(addressAC, { types: ['geocode'] } );

				
				/*
				 * Generate the AutoComplete Fields
				 * End
				 * 
				 */

				/* 
				 * Event Change for Form Settings
				 * Start
				 * 
				 */

				var inputShow = [
					'#edit-transport-car',
					'#edit-transport-public-transport',
					'#edit-transport-bike',
					'#edit-transport-walker',
					'#edit-btn-switch',
					'#edit-btn-minimize-restore',
					'#edit-sl-start',
					'#edit-sl-end'
				];
				
				$(inputShow.join(',')).on("change", function(){

					var element = $(this);
					setTimeout(function(){ 
						var showElement	= element.prop( "checked" ),
							nameElement	= element.attr('name')
											.replace("btn_", "")
											.replace("sl_", "label_")
											.replace("transport[", "")
											.replace("]", "-logo"),
							$elChange 	= $('#'+nameElement);

						switch (showElement) {
						  case true:

						  	$elChange.removeClass('hidden');

						    break;

						  case false:

						  	$elChange.addClass('hidden');
						  	
						    break;
						}

					}, 100,$(this));
				});

				var inputText = [
					'#edit-ct-start-pl',
					'#edit-ct-start',
					'#edit-ct-end',
					'#edit-ct-btn',
					'#edit-title',
					'#edit-address'
				];
				
				$(inputText.join(',')).on("change", function(){
					var element = $(this);
					setTimeout(function(){ 

						var showElement	= element.prop( "checked" ),
							nameElement	= element.attr('name').replace("ct_", "label_"),
							valElement  = element.val(),
							$elChange 	= $('#'+nameElement);
						console.log(nameElement);
						console.log($elChange);
						console.log(valElement);

						switch (nameElement) {
						  case 'label_start_pl':

							nameElement = nameElement.replace("label_", "").replace("_pl", "");
						  	$('#'+nameElement).attr('placeholder', valElement);

						    break;/*

						  case 'address':

						  	$('#title').val(valElement);

						    break;*/

						  default:

					  		$elChange.html(valElement);
						  	
						    break;
						}

					}, 100,$(this));
				});


				/* 
				 * Event Change for Form Settings
				 * End
				 * 
				 */

			});


		}//End function attach
	};//End Bahaviors refreshForm


	//////////////////////////////////////////////////
	///                                            ///
	///           FUNCTION REFRESH FORM            ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////


})(jQuery, Drupal, drupalSettings);



