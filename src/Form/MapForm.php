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
    return 'map_form';
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
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['map_center'] = array(
      '#type'         => 'fieldset',
      '#title'        => $this->t('Default Map Center'),
    );


    $form['map_center']['address_or_coordinate'] = array(
      '#type'           => 'radios',
      '#title'          => $this->t('Set the default map center with a'),
      '#default_value'  => $this->configCr->get('map.address_or_coordinate'),
      '#options'        => array(
                          "address"     => $this->t('Physic Address'),
                          "coordinates" => $this->t('Coordinate (Latitude/Longitude)'),
                        ),
    );

    $form['map_center']['reset_marker'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Reset Marker'),
      '#default_value'  => $this->configCr->get('form.sl_start'),
      '#description'    => $this->t("Reset the Location Marker with the Location Map Settings"),
    );


    $form['map_center']['address'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Address'),
      '#size'           => 80,
      '#prefix'         => '<div id="map_settings_address">',
      '#suffix'         => '</div>',
      '#default_value'  => $this->configCr->get('map.address'),
      '#description'    => $this->t('Entering an address allows- you to automatically fill in the coordinate fields'),
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

    $form['zoom-settings'] = array(
      '#type'   => 'fieldset',
      '#title'  => $this->t('Zoom settings'),
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
      '#type'           => 'select',
      '#title'          => $this->t('Zoom scrolling'),
      '#options'        => array(
        'true'          => $this->t('Enable'),
        'false'         => $this->t('Disable')
      ),
      '#default_value'  => $this->configCr->get('map.zoom_scroll')
    );

    $form['map_type'] = array(
      '#type'           => 'select',
      '#title'          => $this->t('Map type'),
      '#options'        => array(
                          'roadmap'   => $this->t('RoadMap'),
                          'satellite' => $this->t('Satellite'),
                          'hybrid'    => $this->t('Hybrid'),
                          'terrain'   => $this->t('Terrain')
                        ),
      '#default_value'  => $this->configCr->get('map.map_type')
    );

    $form['enable_geoloc'] = array(
      '#type'           => 'select',
      '#title'          => $this->t('Enable Géolocation'),
      '#options'        => array(
                          'true'  => $this->t('Enable'),
                          'false' => $this->t('Disable')
                        ),
      '#default_value'  => $this->configCr->get('map.enable_geoloc')
    );

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

    $this->saveOtherConfigValue($form_state,
    [
      'address_or_coordinate',
      'zoom',
      'zoom_max',
      'zoom_scroll',
      'map_type',
      'enable_geoloc'
    ]);

    $this->saveLocationMap($form_state);

    $this->entityTypeManager->getViewBuilder('block')->resetCache();

    parent::submitForm($form, $form_state);

  }


  public function saveOtherConfigValue($form_state,$otherConfigValue){
    for ($i=0; $i < count($otherConfigValue); $i++) { 
      $old = $this->configCr->get('map.'.$otherConfigValue[$i]);
      $new = $form_state->getValue($otherConfigValue[$i]);
      if ( $old !== $new) {
        $this->configCr->set( 'map.'.$otherConfigValue[$i] , $form_state->getValue($otherConfigValue[$i]));
      }
      
    }
    return $this->configCr->save();
  }

  public function saveLocationMap($form_state){

    $config = array('map');
    $apiKey = $this->configCr->get('api_key');
    $resetMarker = $form_state->getValue('reset_marker');

    switch ($resetMarker) {
      case 1:
        $config[] = 'marker';
        break;
    }
    

    switch ($form_state->getValue('address_or_coordinate')) {
      case 'address':

        $oldAddress = $this->configCr->get('map.address');
        $newAddress = $form_state->getValue('address');

        if($oldAddress !== $newAddress || $resetMarker == 1){

          $addressObject = $this->getLocation($apiKey,$newAddress);

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

          $oldLat = $this->configCr->get('map.latitude');
          $newLat = $form_state->getValue('latitude');
          $oldLng = $this->configCr->get('map.longitude');
          $newLng = $form_state->getValue('longitude');

          if ($oldLat != $newLat || $oldLng != $newLng || $resetMarker == 1) {

            $addressObject = $this->getLocation($apiKey, 'false', $newLat, $newLng);

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


  public function getLocation($apiKey,$address, $lat="", $lng=""){

      $address = urlencode ( $address );

      switch ($address) {
        case 'false':
          $location  = "&latlng=".$lat.",".$lng;
          break;
        
        default:
          $location  = "&address=".$address;
          break;
      }

      $urlToTest = "https://maps.googleapis.com/maps/api/geocode/json?key=".$apiKey.$location;

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $urlToTest);
      $result = curl_exec($ch);
      curl_close($ch);

      return json_decode($result);
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
