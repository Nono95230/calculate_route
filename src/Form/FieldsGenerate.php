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


    $form['map_center']['address_or_coordinate'] = array(
      '#type'           => 'radios',
      '#title'          => $this->t('Set the default map center with a'),
      '#default_value'  => $this->configCr->get('map.address_or_coordinate'),
      '#options'        => array(
                          "address"     => $this->t('Physical Address'),
                          "coordinates" => $this->t('Coordinates (Latitude/Longitude)'),
                        ),
      '#description'    => '<h6>'.$this->t('Vous pouvez choisir le centrage de la carte Google avec une adresse ou des coordonnées géographique !').'</h6>',
    );
  }

  protected function setField( array &$form, $hisDetails, $fieldName, $fieldParams) {

    foreach ($fieldParams as $paramKey => $paramValue) {


      switch ($paramKey) {
        case 'type':
        case 'size':
        case 'prefix':
        case 'suffix':
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
          // @todo : Reprendre ICI
          break;
      }
    }
    if (isset($fieldParams['default_value'])) {
      if (!empty($fieldParams['default_value'])) {
        $defaultValue = $this->settingsConfig[$fieldParams['default_value']];
        $form[$hisDetails][$fieldName]['#default_value'] = $defaultValue;
      }
    }
    else {
      $defaultValue = $this->settingsConfig[$fieldName];
      $form[$hisDetails][$fieldName]['#default_value'] = $defaultValue;
    }


    // Pour tester et avancer dans le code
    // @todo : a supprimer plus tard
    $fieldNameArray = [
      'address_or_coordinate',
      'reset_marker',
    ];
    if (!in_array($fieldName, $fieldNameArray)) {
      kint($fieldName);
      kint($fieldParams);
      kint($form);
      die;
    }
  }




}
