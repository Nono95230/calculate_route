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
					'#edit-address'
				];
				inputSettings = inputSettings.join(',');

				$(inputSettings).on("change", function(){

					var element = $(this);

					setTimeout(function(){

						var settings = getSettings(element);
			  		changeSettings($(settings.s), settings.p, settings.v);

					}, 100,$(this));
				});

				var inputSettings = [
					'#edit-ct-start-pl',
					'#edit-ct-start',
					'#edit-ct-end',
					'#edit-ct-btn',
					'#edit-title'
				];
				inputSettings = inputSettings.join(',');

				$(inputSettings).on('keyup', function(){

					var element = $(this);

					setTimeout(function(){

						var settings = getSettings(element);
			  		changeSettings($(settings.s), settings.p, settings.v);

					}, 100,$(this));
				});

				function getSettings(element){

						var selector = element.data('selector'),
							property = element.data('property'),
							value 	 = '';

						switch (property) {
							case 'color':
							case 'background-color':
								value = '#'+element.val();
								break;

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

						var object = {
							s: selector,
							p: property,
							v: value
						};

						return object;
				}

				function changeSettings($selector, property, value){
					// console.log($selector);
					// console.log(property);
					// console.log(value);
					switch (property) {
						case 'color':
						case 'background-color':
						case 'width':
						case 'height':
						case 'top':
						case 'bottom':
						case 'left':
						case 'right':
							// console.log("success");
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
						case 'checkboxes':
							return (value == true) ? $selector.removeClass('hidden') : $selector.addClass('hidden') ;
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



