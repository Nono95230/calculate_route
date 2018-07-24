/**
 * @file
 * jQuery to provide summary information inside vertical tabs.
 */

(function ($) {

  'use strict';

  Drupal.behaviors.settings_marker = {
    attach: function (context) {

      // Provide summary during Marker Position configuration.
      $('details#edit-marker-position', context).drupalSetSummary(function (context) {

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

      // Provide summary during Info Text configuration.
      $('details#edit-info-text', context).drupalSetSummary(function (context) {

        var vals = [],
            state,
            element = [
              '#edit-enable-info-window'
            ],
            elName,
            elStatus;

        vals.push(Drupal.t('Marker Title :') + '<br/><em>' + $('#edit-title', context).val()+'</em>');

        for (var i = 0; i < element.length; i++) {
          elName = element[i].replace("#edit-enable-", "").replace("-", " ");

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

