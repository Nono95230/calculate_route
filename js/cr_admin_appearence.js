(function($, Drupal, drupalSettings){

	'use strict';


	//////////////////////////////////////////////////
	///                                            ///
	///         FUNCTION REFRESH APPEARENCE        ///
	///                    START                   ///
	///                                            ///
	//////////////////////////////////////////////////

	Drupal.behaviors.refreshAppearence = {
		attach : function(context, settings) {

			$(document).ready( function(){

				/*
				 * Event Change for Appearence Settings
				 * Start
				 *
				 */

				var inputSettings = [
					'#edit-width-map',
					'#edit-height-map',
					'#edit-top-position',
					'#edit-bottom-position',
					'#edit-left-position',
					'#edit-right-position',
					'#edit-label-text-color--2',
					'#edit-button-text-color--2',
					'#edit-head-color--2',
					'#edit-form-color--2',
					'#edit-button-color--2',
					'#edit-three-btn-color--2'
				];
				inputSettings = inputSettings.join(',');

				$(inputSettings).on("change", function(){

					var element = $(this);

					setTimeout(function(){

						var settings = getSettings(element);
			  		changeSettings($(settings.s), settings.p, settings.v);

					}, 100,$(this));
				});

				var color = '';
				$('.btn-icon, .icon-fa').hover(function(){
					color = $('#edit-three-btn-hover-color--2').val();
				    changeSettings($(this), 'color', '#'+color);
			    }, function(){
					color = $('#edit-three-btn-color--2').val();
				    changeSettings($(this), 'color', '#'+color);
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
					console.log($selector);
					console.log(property);
					console.log(value);
					switch (property) {
						case 'color':
						case 'background-color':
						case 'width':
						case 'height':
						case 'top':
						case 'bottom':
						case 'left':
						case 'right':
							console.log("success");
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
				 * Event Change for Appearence Settings
				 * End
				 *
				 */

			});


		}//End function attach
	};//End Bahaviors refreshAppearence


	//////////////////////////////////////////////////
	///                                            ///
	///         FUNCTION REFRESH APPEARENCE        ///
	///                    END                     ///
	///                                            ///
	//////////////////////////////////////////////////


})(jQuery, Drupal, drupalSettings);



