/**
 * @file
 * jQuery to provide summary information inside vertical tabs.
 */
;
(function ($) {

  'use strict';

  Drupal.behaviors.settings_appearence = {
    attach: function (context) {

      var summary = {
        map_dimension: [
          {
            title: 'Largeur',
            type: 'input-group',
            element: 'edit-width-map'
          },
          {
            title: 'Hauteur',
            type: 'input-group',
            element: 'edit-height-map'
          }
        ],
        form_position: [
          {
            title: 'Top',
            type: 'input-group',
            element: 'edit-top-position'
          },
          {
            title: 'Bottom',
            type: 'input-group',
            element: 'edit-bottom-position'
          },
          {
            title: 'Left',
            type: 'input-group',
            element: 'edit-left-position'
          },
          {
            title: 'Right',
            type: 'input-group',
            element: 'edit-right-position'
          }
        ],
        form_color_text: [
          {
            title: 'Label color',
            type: 'color',
            element: 'edit-label-text-color--2'
          },
          {
            title: 'Submit Button Color',
            type: 'color',
            element: 'edit-button-text-color--2'
          }
        ],
        form_color_background: [
          {
            title: 'Head color',
            type: 'color',
            element: 'edit-head-color--2'
          },
          {
            title: 'Form Color',
            type: 'color',
            element: 'edit-form-color--2'
          },
          {
            title: 'Submit Button Color',
            type: 'color',
            element: 'edit-button-color--2'
          }
        ],
        form_color_button: [
          {
            title: 'Button color',
            type: 'color',
            element: 'edit-three-btn-color--2'
          },
          {
            title: 'Button Hover Color',
            type: 'color',
            element: 'edit-three-btn-color--2'
          }
        ]
      };

      // Provide summary during Map Dimension configuration.
      $('details#edit-dimension-map', context).drupalSetSummary(function (context) {

        var vals = [],
            summaryElements = summary.map_dimension;

        displaySummary(summaryElements, vals);

        return vals.join('<br/>');

      });

      // Provide summary during Form Position configuration.
      $('details#edit-form-position', context).drupalSetSummary(function (context) {

        var vals = [],
            summaryElements = summary.form_position;

        displaySummary(summaryElements, vals);

        return vals.join('<br/>');

      });

      // Provide summary during Form Color Text configuration.
      $('details#edit-form-color-text', context).drupalSetSummary(function (context) {

        var vals = [],
            summaryElements = summary.form_color_text;

        displaySummary(summaryElements, vals);

        return vals.join('<br/>');

      });

      // Provide summary during Form Color Background configuration.
      $('details#edit-form-color-bg', context).drupalSetSummary(function (context) {

        var vals = [],
            summaryElements = summary.form_color_background;

        displaySummary(summaryElements, vals);

        return vals.join('<br/>');

      });

      // Provide summary during Form Color Three Bouton configuration.
      $('details#edit-form-color-three-btn', context).drupalSetSummary(function (context) {

        var vals = [],
            summaryElements = summary.form_color_button;

        displaySummary(summaryElements, vals);

        return vals.join('<br/>');

      });

      function displaySummary(elements, vals){

        for (var i = 0; i < elements.length; i++) {
          if (elements[i].type == 'input-group') {
            var selector  = elements[i].element,
                value     =  $('#'+selector, context).val(),
                unity     =  $('#'+selector+'-unity' , context).val();
            if ( 'auto' != unity ) {
              vals.push(Drupal.t(elements[i].title+' :')+' <em>'+ value+unity +'</em>');
            }
            else{
              if ( elements[i].element.indexOf('position') < 0 ) {
                vals.push(Drupal.t(elements[i].title+' :')+' <em>'+ unity +'</em>');
              }
            }
          }
          if (elements[i].type == 'color') {
            var selector  = elements[i].element,
                value     =  '#'+$('#'+selector, context).val();

            vals.push(Drupal.t(elements[i].title+' :')+' <em>'+ value +'</em>');
          }
        }

      }

    }
  };

})(jQuery);

