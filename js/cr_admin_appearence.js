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

				// Property saved for appearence
				var aps 								= drupalSettings.calculate_route.JS.appearence_settings,
						inputRecordSettings = {
																		width_map: 					aps.width_map,
																		height_map: 				aps.height_map,
																		top_position: 			aps.top_position,
																		bottom_position: 		aps.bottom_position,
																		left_position: 			aps.left_position,
																		right_position: 		aps.right_position,
																		label_text_color: 	"#" + aps.label_text_color,
																		button_text_color: 	"#" + aps.button_text_color,
																		head_color: 				"#" + aps.header_color,
																		form_color: 				"#" + aps.form_color,
																		button_color: 			"#" + aps.button_color,
																		three_btn_color: 		"#" + aps.three_btn_color
																		//three_btn_hover_color: 	"#"+aps.three_btn_hover_color
																	};

				var inputRefreshSettings = [
																		'#edit-width-map',
																		'#edit-width-map-unity',
																		'#edit-height-map',
																		'#edit-height-map-unity',
																		'#edit-top-position',
																		'#edit-top-position-unity',
																		'#edit-bottom-position',
																		'#edit-bottom-position-unity',
																		'#edit-left-position',
																		'#edit-left-position-unity',
																		'#edit-right-position',
																		'#edit-right-position-unity',
																		'#edit-label-text-color--2',
																		'#edit-button-text-color--2',
																		'#edit-head-color--2',
																		'#edit-form-color--2',
																		'#edit-button-color--2',
																		'#edit-three-btn-color--2'
																	];
				inputRefreshSettings = inputRefreshSettings.join(',');

				$(inputRefreshSettings).on("change", function(){

					var element = $(this);

					setTimeout(function(){

						var settings = getSettings(element);

		  			changeSettings($(settings.s), settings.p, settings.v);

			  		console.log(settings);

          	//elName = element[i].replace(/#edit-|transport-/g, "").replace(/-/g, " ");

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

						var name 			= element.attr('id').replace("edit-", "").replace("-unity", "").replace("--2", ""),
								selector 	= element.data('selector'),
								property 	= element.data('property'),
								value 	 	= '';

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
								var issetUnity 		= '-unity',
										selectorValue = '',
										selectorUnity = '',
										elementValue 	= '',
										elementUnity 	= '';

								if ( element.attr('id').indexOf(issetUnity) >= 0 ) {
									selectorValue = $('#'+element.attr('id').replace(issetUnity, '') );
									selectorUnity = element;

									elementUnity 	= selectorUnity.val();
								}
								else {
									selectorValue = element;
									selectorUnity = $('#'+element.attr('id')+issetUnity);

									if ( selectorUnity.length ) {
										elementUnity = selectorUnity.val();
									}
								}
								if ( 'auto' == elementUnity && selectorUnity.attr('id').indexOf('position') >= 0 ) {
									elementValue = '';
								}
								else{
									if (selectorValue.val() == '') {
										selectorValue.val('0');
									}
									elementValue = selectorValue.val();
								}

								value = elementValue + elementUnity;
						}
						// console.log(selector);
						// console.log(property);
						// console.log(value);

						var object = {
							n: name,
							s: selector,
							p: property,
							v: value
						};

						return object;
				}

				function changeSettings($selector, property, value){
					//console.log($selector);
					//console.log(property);
					//console.log(value);
					switch (property) {
						case 'color':
						case 'background-color':
						case 'width':
						case 'height':
						case 'top':
						case 'bottom':
						case 'left':
						case 'right':
							//console.log("success");
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



