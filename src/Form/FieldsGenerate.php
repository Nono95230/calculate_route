<?php

namespace Drupal\calculate_route\Form;

use Symfony\Component\Yaml\Yaml;

/**
 * Class FieldsGenerate.
 */
class FieldsGenerate{

  /**
   * @var string $settingsName
   */
  protected $settingsName;

  /**
   * @var array $settingsConfig
   */
  protected $settingsConfig;

  /**
   * @var string $settingsPath
   */
  protected $settingsPath;

  /**
   * @var string $settingsDetailsPath
   */
  protected $settingsDetailsPath;

  /**
   * @var array $settingsDetails
   */
  protected $settingsDetails;

  /**
   * @var string $settingsFieldsDir
   */
  protected $settingsFieldsDir;


  public function __construct($settingsName){
    $this->settingsName = $settingsName;
    $this->settingsConfig = \Drupal::config('calculate_route.config')->get($settingsName);

    $this->settingsPath = drupal_get_path(
      'module',
      'calculate_route'
    ) . '/mapping/settings/';

    $this->settingsDetailsPath = $this->settingsPath;
    $this->settingsDetailsPath .= $settingsName . '.settings.yml';

    $this->settingsFieldsDir = $this->settingsPath;
    $this->settingsFieldsDir .= $settingsName . '.details/';

    if (file_exists($this->settingsDetailsPath)) {
      $this->settingsDetails = Yaml::parseFile($this->settingsDetailsPath);
    }

  }


  /**
   * Generate a complete form.
   *
   * @param array $form
   *   Contains the form.
   * @param string $settings_name
   *   Contains the settings name.
   *
   */
  public function generateForm(array &$form) {

    $settingsName = 'settings_' . $this->settingsName;

    $form[$settingsName] = [
      '#type' => 'vertical_tabs',
      '#default_tab' => $this->settingsDetails['default_tab'],
      '#attached' => [
        'library' => [
          'calculate_route/' . $this->settingsName . '_v-tabs'
        ]
      ]
    ];

    $details = $this->settingsDetails['details'];
    foreach ($details as $detailsName => $detailsLabel) {
      $form[$detailsName] = array(
        '#type' => 'details',
        '#title' => t($detailsLabel),
        '#group' => $settingsName,
      );

      $detailsFieldsPath = $this->settingsFieldsDir . $detailsName . '.fields.yml';

      if (file_exists($detailsFieldsPath)) {

        $detailsFields = Yaml::parseFile($detailsFieldsPath);

        foreach ($detailsFields as $fieldName => $fieldParams) {
          $this->setField($form, $detailsName, $fieldName, $fieldParams);

        }

      }

    }

  }

  protected function setField( array &$form, $hisDetails, $fieldName, $fieldParams) {

    foreach ($fieldParams as $paramKey => $paramValue) {


      switch ($paramKey) {
        case 'type':
        case 'size':
        case 'min':
        case 'max':
        case 'prefix':
        case 'suffix':
        case 'format':
          if (!empty($paramValue)) {
            $form[$hisDetails][$fieldName]["#$paramKey"] = $paramValue;
          }
          break;

        case 'title':
          if (!empty($paramValue)) {
            $form[$hisDetails][$fieldName]["#$paramKey"] = t($paramValue);
          }
          break;

        case 'description':
          if (
            !empty($paramValue) &&
            is_array($paramValue) &&
            !empty($paramValue['content'])
          ) {
            if (!empty($paramValue['balise'])) {
              $balise = $paramValue['balise'];
              $description = "<$balise>" . t($paramValue['content']) . "</$balise>";
              $form[$hisDetails][$fieldName]["#$paramKey"] = $description;
            }
            else {
              $form[$hisDetails][$fieldName]["#$paramKey"] = t($paramValue['content']);
            }
          }
          break;

        case 'options':
          if (!empty($paramValue) && is_array($paramValue)) {
            foreach ($paramValue as $optionKey => $optionValue) {
              $form[$hisDetails][$fieldName]["#$paramKey"][$optionKey] = t($optionValue);
            }
          }
          break;

        case 'states':
          if (!empty($paramValue) && is_array($paramValue)) {
            foreach ($paramValue as $state => $stateCondition) {
              if (
                !empty($stateCondition) &&
                is_array($stateCondition) &&
                !empty($stateCondition['target']) &&
                !empty($stateCondition['response']) &&
                is_string($stateCondition['target']) &&
                is_array($stateCondition['response'])
              ) {
                $form[$hisDetails][$fieldName]["#$paramKey"][$state] = [
                  $stateCondition['target'] => $stateCondition['response']
                ];
              }
            }
          }
          break;
      }
    }

    if (isset($fieldParams['default_value'])) {
      if (!empty($fieldParams['default_value'])) {
        if (!empty($this->settingsConfig[$fieldParams['default_value']])) {
          $defaultValue = $this->settingsConfig[$fieldParams['default_value']];
          $form[$hisDetails][$fieldName]['#default_value'] = $defaultValue;
        }
      }
    }
    else {
      if (!empty($this->settingsConfig[$fieldName])) {
        $defaultValue = $this->settingsConfig[$fieldName];
        $form[$hisDetails][$fieldName]['#default_value'] = $defaultValue;
      }

      if (!empty($fieldParams['type']) && 'checkbox' == $fieldParams['type']) {
        if (
          !empty($this->settingsConfig[$fieldName]) &&
          1 == $this->settingsConfig[$fieldName]
        ) {
          $form[$hisDetails][$fieldName]['#attributes']['checked'] = 'checked';
        }
      }

    }

    // Pour tester et avancer dans le code
    // @todo : a supprimer plus tard
    /*
    $fieldNameArray = [
      'address_or_coordinate',
      'reset_marker',
      'address',
      'latitude',
      'longitude',
      'enable_geoloc',
      'map_type',
      'zoom',
      'zoom_max',
      'zoom_scroll',
    ];
    if (!in_array($fieldName, $fieldNameArray)) {
      kint($hisDetails);
      kint($fieldName);
      kint($fieldParams);
      kint($form);
      die;
    }
    */
  }




}
