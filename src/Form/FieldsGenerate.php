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
      $this->$settingsDetails = Yaml::parseFile($this->settingsDetailsPath);
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

    kint($form);
    kint($this->$settingsName);
    kint($this->settingsConfig);
    kint($this->settingsPath);
    kint($this->settingsDetailsPath);
    kint($this->settingsDetails);
    kint($this->settingsFieldsDir);
    die;




    $form['settings_map'] = array(
      '#type' => 'vertical_tabs',
      '#default_tab' => 'edit-map-center',
      '#attached' => array(
        'library' => array(
          'calculate_route/map_v-tabs'
        )
      )
    );

    $form['map_center'] = array(
      '#type' => 'details',
      '#title' => $this->t('Map Center'),
      '#group' => 'settings_map',
    );

  }


}
