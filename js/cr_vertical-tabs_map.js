/**
 * @file
 * jQuery to provide summary information inside vertical tabs.
 */

(function ($) {

  'use strict';

  Drupal.behaviors.settings_map = {
    attach: function (context) {

      // Provide summary during Map Center configuration.
      $('details#edit-map-center', context).drupalSetSummary(function (context) {

        var vals = [],
            element = '#edit-reset-marker',
            elName,
            elStatus;

        switch($('input[name="address_or_coordinate"]:checked', context).val()) {
          case 'address':
            vals.push(Drupal.t('With address :'));
            vals.push(Drupal.t('<em>'+ $('#edit-address', context).val() +'</em>'));
            break;
          case 'coordinates':
            vals.push(Drupal.t('With coordinates :'));
            vals.push('<em>' + Drupal.t('Latitude :') +' '+ $('#edit-latitude', context).val() +'</em>');
            vals.push('<em>' + Drupal.t('Longitude :') +' '+ $('#edit-longitude', context).val() +'</em>');
            break;
        }

        if ($(element, context).is(':checked')) {
          vals.push('<span class="enable-element"><i class="fa fa-check fa-lg"></i><em>'
                + element.replace("#edit-", "").replace("-", " ") +' Location'
                + '</em></span>');
        }

        return vals.join('<br/>');

      });

      // Provide summary during Geolocation configuration.
      $('details#edit-geoloc', context).drupalSetSummary(function (context) {

        var vals = [],
            summaryResponse,
            state;

        if ($('#edit-enable-geoloc', context).is(':checked')) {
          summaryResponse = 'Enable';
          state = 'check';
        }
        else{
          summaryResponse = 'Disabled';
          state = 'close';
        }
        
        vals.push('<span class="enable-element"><i class="fa fa-'+state+' fa-lg"></i><em>'
              + Drupal.t(summaryResponse)
              + '</em></span>');

        return vals.join('<br/>');

      });

      // Provide summary during Map Type configuration.
      $('details#edit-set-map-type', context).drupalSetSummary(function (context) {

        var vals = [],
            summaryResponse;

        switch($('#edit-map-type', context).val()) {
          case 'roadmap':
            summaryResponse = 'Roadmap';
            break;
          case 'satellite':
            summaryResponse = 'Satellite';
            break;
          case 'hybrid':
            summaryResponse = 'Hybrid';
            break;
          case 'terrain':
            summaryResponse = 'Terrain';
            break;
        }

        vals.push(Drupal.t(summaryResponse));

        return vals.join('<br/>');

      });

      // Provide summary during Zoom configuration.
      $('details#edit-zoom-settings', context).drupalSetSummary(function (context) {

        var vals = [],
            defaultZoom = $('#edit-zoom', context).val(),
            ZoomMax = $('#edit-zoom-max', context).val(),
            element = [
              '#edit-zoom-scroll'
            ],
            elName,
            elStatus;

        if (defaultZoom) {
          vals.push(Drupal.t('Default Zoom : '+defaultZoom));
        }

        if (ZoomMax) {
          vals.push(Drupal.t('Zoom Max : '+ZoomMax));
        }

        for (var i = 0; i < element.length; i++) {
          elName = element[i].replace("#edit-", "").replace("-", " ");

          switch($(element[i], context).is(':checked')) {
            case true:
              elStatus = 'check';
              break;
            case false:
              elStatus = 'close';
              break;
          }

          vals.push(Drupal.t('<span class="enable-element"><i class="fa fa-'+elStatus+' fa-lg"></i><em>'+elName+'</em></span>'));

        }

        return vals.join('<br/>');

      });

    }
  };

})(jQuery);

