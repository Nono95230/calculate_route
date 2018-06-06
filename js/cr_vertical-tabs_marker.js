/**
 * @file
 * jQuery to provide summary information inside vertical tabs.
 */

(function ($) {

  'use strict';

  Drupal.behaviors.settings_marker = {
    attach: function (context) {

      // Provide summary during Map Center configuration.
      $('details#edit-map-center', context).drupalSetSummary(function (context) {

        var vals = [];

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

        if ($('#edit-reset-marker', context).is(':checked')) {
          vals.push(Drupal.t('And resets the Marker\'s location'));
        }

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
            ZoomMax = $('#edit-zoom-max', context).val();

        if (defaultZoom) {
          vals.push(Drupal.t('Default Zoom : '+defaultZoom));
        }

        if (ZoomMax) {
          vals.push(Drupal.t('Zoom Max : '+ZoomMax));
        }

        if ($('#edit-zoom-scroll', context).is(':checked')) {
          vals.push(Drupal.t('Zoom Scrolling enabled'));
        }
        else{
          vals.push(Drupal.t('Zoom Scrolling disabled'));
        }

        return vals.join('<br/>');

      });

      // Provide summary during Geolocation configuration.
      $('details#edit-geoloc', context).drupalSetSummary(function (context) {

        var vals = [],
            summaryResponse;

        if ($('#edit-enable-geoloc', context).is(':checked')) {
          summaryResponse = 'Enable';
        }
        else{
          summaryResponse = 'Disabled';
        }

        vals.push(Drupal.t(summaryResponse));

        return vals.join('<br/>');

      });

    }
  };

})(jQuery);

