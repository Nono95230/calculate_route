<?php

namespace Drupal\calculate_route\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactory;


/**
 * Class MapForm.
 */
class MapForm extends ConfigFormBase {

  protected $entityTypeManager;
  protected $configCr;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, ConfigFactory $config){
    $this->entityTypeManager  = $entityTypeManager;
    $this->configCr           = $config->getEditable("calculate_route.config");
  }

  public static function create(ContainerInterface $container){
    return new static(
      $container->get('entity_type.manager'),
      $container->get('config.factory')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings__map';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'calculate_route.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state){

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

    $form['geoloc'] = array(
      '#type' => 'details',
      '#title'  => $this->t('Géolocation'),
      '#group' => 'settings_map',
    );

    $form['set_map_type'] = array(
      '#type' => 'details',
      '#title'  => $this->t('Map type'),
      '#group' => 'settings_map',
    );

    $form['zoom-settings'] = array(
      '#type' => 'details',
      '#title'  => $this->t('Zoom settings'),
      '#group' => 'settings_map',
    );

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

    $form['map_center']['reset_marker'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Reset Marker'),
      '#description'    => $this->t("Reset the Location Marker with the Location Map Settings"),
    );

    $form['map_center']['address'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Address'),
      '#size'           => 80,
      '#prefix'         => '<div id="map_settings_address">',
      '#suffix'         => '</div>',
      '#default_value'  => $this->configCr->get('map.address'),
      '#states'         => array(
                          'invisible' => array(
                            'input[name="address_or_coordinate"]' => array('value' => "coordinates")
                          ),
                        ),
    ];

    $form['map_center']['latitude'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Latitude'),
      '#default_value'  => $this->configCr->get('map.latitude'),
      '#states'         => array(
                          'invisible' => array(
                            'input[name="address_or_coordinate"]' => array('value' => "address")
                          ),
                        ),
    ];

    $form['map_center']['longitude'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Longitude'),
      '#default_value'  => $this->configCr->get('map.longitude'),
      '#states'         => array(
                          'invisible' => array(
                            'input[name="address_or_coordinate"]' => array('value' => "address")
                          ),
                        ),
    ];

    $form['geoloc']['enable_geoloc'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Enabled'),
      '#description'    => '<h6>'.$this->t('Le service dé géolocalisation ne fonctionnera pas sur un serveur en local, actif seulement en ligne..').'</h6>',
    );

    if ($this->configCr->get('map.enable_geoloc') == 1) {
      $form['geoloc']['enable_geoloc']['#attributes'] = array('checked' => 'checked');
    }

    $form['set_map_type']['map_type'] = array(
      '#type'           => 'select',
      '#options'        => array(
                          'roadmap'   => $this->t('RoadMap'),
                          'satellite' => $this->t('Satellite'),
                          'hybrid'    => $this->t('Hybrid'),
                          'terrain'   => $this->t('Terrain')
                        ),
      '#default_value'  => $this->configCr->get('map.map_type')
    );

    $form['zoom-settings']['zoom'] = [
      '#type'           => 'textfield',
      '#min'            => 0,
      '#max'            => 21,
      '#size'           => 7,
      '#title'          => $this->t('Default zoom'),
      '#default_value'  => $this->configCr->get('map.zoom')
    ];
    $form['zoom-settings']['zoom_max'] = [
      '#type'           => 'textfield',
      '#min'            => 0,
      '#max'            => 21,
      '#size'           => 7,
      '#title'          => $this->t('Zoom maximum authorized'),
      '#default_value'  => $this->configCr->get('map.zoom_max')
    ];

    $form['zoom-settings']['zoom_scroll'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Enable Zoom scrolling'),
    );

    if ($this->configCr->get('map.zoom_scroll') == 1) {
      $form['zoom-settings']['zoom_scroll']['#attributes'] = array('checked' => 'checked');
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   *//*
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }*/


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $configName = str_replace("settings__","",$this->getFormId());

    $this->saveOtherConfigValue($form_state, $configName,
    [
      'address_or_coordinate',
      'zoom',
      'zoom_max',
      'zoom_scroll',
      'map_type',
      'enable_geoloc'
    ]);

    $resetMarker = $form_state->getValue('reset_marker');
    $config = array($configName);

    switch ($resetMarker) {
      case 1:
        $config[] = 'marker';
        break;
    }

    $this->saveLocationMap($form_state, $config);

    $this->entityTypeManager->getViewBuilder('block')->resetCache();

    parent::submitForm($form, $form_state);

  }


  public function saveOtherConfigValue($form_state, $type, $otherConfigValue){
    for ($i=0; $i < count($otherConfigValue); $i++) {

      $old = $this->configCr->get($type.'.'.$otherConfigValue[$i]);
      $_new = $form_state->getValue($otherConfigValue[$i]);
      $new  = (is_array ( $_new ) && array_key_exists('value', $_new) ? $_new['value'] : $_new );
      if ( $old !== $new) {
        $this->configCr->set( $type.'.'.$otherConfigValue[$i] , $new);
      }

    }
    return $this->configCr->save();
  }


  public function saveLocationMap($form_state, $config){

    $apiKey = $this->configCr->get('api_key');

    switch ($form_state->getValue('address_or_coordinate')) {
      case 'address':

        $oldAddress = $this->configCr->get($config[0].'.address');
        $newAddress = $form_state->getValue('address');

        if($oldAddress !== $newAddress || count($config) > 1){
          $urlToTest = $this->getUrlToTest($apiKey,$newAddress);
          $addressObject = $this->getAdressObject($urlToTest);

          if ( $this->isAddressValid($addressObject) ) {

            $this->setLocationSettings([
              'config'    => $config,
              'address'   => $newAddress,
              'latitude'  => $addressObject->results[0]->geometry->location->lat,
              'longitude' => $addressObject->results[0]->geometry->location->lng
            ]);

          }

        }

        break;

      case 'coordinates':

          $oldLat = $this->configCr->get($config[0].'.latitude');
          $newLat = $form_state->getValue('latitude');
          $oldLng = $this->configCr->get($config[0].'.longitude');
          $newLng = $form_state->getValue('longitude');

          if ($oldLat != $newLat || $oldLng != $newLng || count($config) > 1) {

            $urlToTest = $this->getUrlToTest($apiKey, 'false', $newLat, $newLng);
            $addressObject = $this->getAdressObject($urlToTest);

            if ( $this->isAddressValid($addressObject) ) {

              $this->setLocationSettings([
                'config'    => $config,
                'address'   => $addressObject->results[0]->formatted_address,
                'latitude'  => $newLat,
                'longitude' => $newLng
              ]);

            }

          }

        break;
    }

    return [];
  }


  public function getUrlToTest($apiKey, $address, $lat="", $lng=""){
      $address = urlencode ( $address );

      switch ($address) {
        case 'false':
          $location  = "&latlng=".$lat.",".$lng;
          break;

        default:
          $location  = "&address=".$address;
          break;
      }

      return "https://maps.googleapis.com/maps/api/geocode/json?key=".$apiKey.$location;
  }


  public function getAdressObject($urlToTest){

      $client = \Drupal::httpClient();

      try{
        $request = $client->post($urlToTest, [
          'json' => [
            'id'=> 'data-explorer'
          ]
        ]);
        $data = $request->getBody();
        $response = json_decode($data);
      }
      catch(\GuzzleHttp\Exception\RequestException $e) {
        watchdog_exception('calculate_route', $e->getMessage());
      }

      return $response;
  }


  public function isAddressValid($testAddress){
    if ($testAddress->status === "OK") {
      return true;
    }
    return false;
  }


  public function setLocationSettings($settings){
    for ($i=0; $i < count($settings['config']); $i++) {
      $this->configCr
        ->set( $settings['config'][$i].'.address', $settings['address'] )
        ->set( $settings['config'][$i].'.latitude', $settings['latitude'] )
        ->set( $settings['config'][$i].'.longitude', $settings['longitude'] );
    }
    return $this->configCr->save();
  }




}
