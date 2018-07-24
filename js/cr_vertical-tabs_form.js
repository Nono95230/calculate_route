/**
 * @file
 * jQuery to provide summary information inside vertical tabs.
 */
;
(function ($) {

  'use strict';

  Drupal.behaviors.settings_form = {
    attach: function (context) {


      // Provide summary during Destination Address configuration.
      $('details#edit-address-destination', context).drupalSetSummary(function (context) {

        var vals = [];

        vals.push(Drupal.t('Address Title : ')+'<br/><em>'+ $('#edit-title' , context).val() +'</em>');

        vals.push(Drupal.t('Address Location : ')+'<br/><em>'+ $('#edit-address' , context).val() +'</em>');

        return vals.join('<br/>');

      });

      // Provide summary during Enable Element configuration.
      $('details#edit-enable-element', context).drupalSetSummary(function (context) {

        var vals = [],
            element = [
              '#edit-transport-car',
              '#edit-transport-public-transport',
              '#edit-transport-bike',
              '#edit-transport-walker',
              '#edit-btn-switch',
              '#edit-btn-minimize-restore'
            ],
            elName,
            elStatus;

        for (var i = 0; i < element.length; i++) {
          elName = element[i].replace(/#edit-|transport-/g, "").replace(/-/g, " ");

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

      // Provide summary during Label Address configuration.
      $('details#edit-label-address', context).drupalSetSummary(function (context) {

        var vals = [],
            element = [
              '#edit-sl-start',
              '#edit-sl-end'
            ],
            elName,
            elStatus,
            elIsGood;

        for (var i = 0; i < element.length; i++) {
          elName = element[i].replace("#edit-sl-", "")
                              + ' label address';

          switch($(element[i], context).is(':checked')) {
            case true:
              elStatus = 'check';
              elIsGood = ' :';
              break;
            case false:
              elStatus = 'close';
              elIsGood = '';
              break;
          }

          vals.push(Drupal.t('<span class="enable-element"><i class="fa fa-'+elStatus+' fa-lg"></i><em>'+elName+elIsGood+'</em></span>'));
          if ( elStatus === 'check' ) {
            vals.push(Drupal.t('<em>'+ $(element[i].replace("sl", "ct"), context).val() +'</em>'));
          }

        }

        return vals.join('<br/>');

      });

      // Provide summary during Customize Texts configuration.
      $('details#edit-other-texts', context).drupalSetSummary(function (context) {

        var vals = [];

        vals.push(Drupal.t('Placeholder start address text : ')+'<br/><em>'+ $('#edit-ct-start-pl' , context).val() +'</em>');

        vals.push(Drupal.t('SUbmit button text : ')+'<br/><em>'+ $('#edit-ct-btn' , context).val() +'</em>');

        return vals.join('<br/>');

      });

    }
  };

})(jQuery);

