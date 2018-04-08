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

				var inputSettings = [
					'#edit-transport-car',
					'#edit-transport-public-transport',
					'#edit-transport-bike',
					'#edit-transport-walker',
					'#edit-btn-switch',
					'#edit-btn-minimize-restore',
					'#edit-sl-start',
					'#edit-sl-end',
					'#edit-ct-start-pl',
					'#edit-ct-start',
					'#edit-ct-end',
					'#edit-ct-btn',
					'#edit-title',
					'#edit-address'
				];
				inputSettings = inputSettings.join(',');

				$(inputSettings).on("change", function(){
					var element = $(this);
					setTimeout(function(){ 

						var selector = element.data('selector'),
							property = element.data('property'),
							value 	 = '';

						switch (property) {
							case 'checkbox':
								value = element.prop('checked');
								break;
							case 'checkboxes':
								value 	 = element.prop('checked');
								selector = element.attr(selector).replace("transport[", "#").replace("]", "-logo");
								break;

							default:
								value = element.val();
								break;
						}

						// console.log(selector);
						// console.log(property);
						// console.log(value);

			  			changeSettings($(selector), property, value);

					}, 100,$(this));
				});

				function changeSettings($selector, property, value){
					switch (property) {
						case 'color':
							return $selector.css(property, '#'+value);
							break;

						case 'width':
						case 'height':
						case 'top':
						case 'bottom':
						case 'left':
						case 'right':
							return $selector.css(property, value);
							break;

						case 'placeholder':
							return $selector.attr(property, value);
							break;

						case 'html':
							return $selector.html(value);
							break;

						case 'val':
							return $selector.val(value);
							break;

						case 'checkbox':
							switch (value) {
							  case true:
							  	return $selector.removeClass('hidden');
							    break;

							  case false:
							  	return $selector.addClass('hidden');
							    break;

							}
							break;

						case 'checkboxes':
							switch (value) {
							  case true:
							  	return $selector.removeClass('hidden');
							    break;

							  case false:
							  	return $selector.addClass('hidden');
							    break;

							}
							break;

					}
				}
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



