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
          $this->setField($detailsName, $fieldName, $fieldParams);
          kint($fieldName);
          kint($fieldParams);
          die;
        }

      }

    }


    kint($form);
    kint($this->settingsName);
    kint($this->settingsConfig);
    // kint($this->settingsPath);
    // kint($this->settingsDetailsPath);
    kint($this->settingsDetails);
    kint($this->settingsFieldsDir);
    die;

  }

  protected function setField($hisDetails, $fieldName, $fieldParams) {
    $fieldType = $fieldParams['type'];

    switch ($fieldType) {
      case 'radios':
       $field = $this->setFieldRadios($hisDetails, $fieldName, $fieldParams);
        break;

    }

    return $field;
  }

  protected function setFieldRadios($hisDetails, $fieldName, $fieldParams) {
    kint($hisDetails);
    kint($fieldName);
    kint($fieldParams);
    die;
    foreach ($fieldParams as $key => $value) {

      $form[$hisDetails][$fieldName]["#$key"] = [];
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


}
