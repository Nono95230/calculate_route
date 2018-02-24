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
/*
    $form['coordinate'] = array(
      '#type'         => 'fieldset',
      '#prefix'       => '<div id="coordinate">',
      '#suffix'       => '</div>',
      '#title'        => $this->t('Default coordinate'),
      '#description'  => '<a href="https://www.coordonnees-gps.fr/">'.$this->t('Récupérer les coordonnées GPS').'</a></br><a href="https://www.gps-coordinates.net/">'.$this->t('Get GPS Coordonninates').'</a>',
    );

    $form['coordinate']['address'] = [
      '#type'           =>  'textfield',
      //'#autocomplete_path' => 'node_reference/autocomplete/node/link/field_contact_reference',
      '#title'          =>  $this->t('Address'),
      '#size'           =>  40,
      '#default_value'  =>   $this->configCr->get('map.address'),
      '#description'    =>  'Entering an address allows you to automatically fill in the coordinate fields',
      '#ajax'           =>  array(
                              'callback'  => array($this,'updateFieldAddress'),
                              'event'     => 'change',
                              'wrapper' => 'coordinate',
                              'method' => 'replace',
                              'effect' => 'fade',
                            ),
      //'#autocomplete_route_name' => 'calculate_route.address.autocomplete',
      //'#autocomplete_route_parameters' => array('field_name' => "address", 'count' => 3),
    ];

    $form['coordinate']['latitude'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Latitude'),
      '#size'           => 23,
      '#default_value'  => $this->configCr->get('map.latitude')
    ];

    $form['coordinate']['longitude'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Longitude'),
      '#size'           => 23,
      '#default_value'  => $this->configCr->get('map.longitude')
    ];*/


    $form['map_center'] = array(
      '#type'         => 'fieldset',
      '#title'        => $this->t('Default Map Center'),
      '#description'  => '<a href="https://www.coordonnees-gps.fr/">'.$this->t('Récupérer les coordonnées GPS').'</a></br><a href="https://www.gps-coordinates.net/">'.$this->t('Get GPS Coordonninates').'</a>',
    );

    $form['map_center']['address'] = [
      '#type'           =>  'textfield',
      '#title'          =>  $this->t('Address'),
      '#size'           =>  80,
      '#default_value'  =>   $this->configCr->get('map.address'),
      '#description'    =>  'Entering an address allows you to automatically fill in the coordinate fields',
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
                          'roadmap'   => 'RoadMap',
                          'satellite' => 'Satellite',
                          'hybrid'    => 'Hybrid',
                          'terrain'   => 'Terrain',
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

    $apiKey     = $this->configCr->get('api_key');
    $oldAddress = $this->configCr->get('map.address');
    $newAddress = $form_state->getValue('address');

    /* Doesn't work - Start */
    // If Address change
    if($oldAddress !== $newAddress){

      $verifyAddress    = $this->verifyAddress($apiKey,$newAddress);
      $isAddressValid   = $this->verifyAddressValidaty($verifyAddress);

      if ( $isAddressValid ) {
        $location = $verifyAddress->results[0]->geometry->location;// ->lat ou ->lng
        kint($location->lat);
      }
    }
    /* Doesn't work - End */

    $this->configCr
        ->set( 'map.address', $form_state->getValue('address') )
        ->set( 'map.latitude', $form_state->getValue('latitude') )
        ->set( 'map.longitude', $form_state->getValue('longitude') )
        ->set( 'map.zoom', $form_state->getValue('zoom') )
        ->set( 'map.zoom_max', $form_state->getValue('zoom_max') )
        ->set( 'map.zoom_scroll', $form_state->getValue('zoom_scroll') )
        ->set( 'map.map_type', $form_state->getValue('map_type') )
        ->set( 'map.enable_geoloc', $form_state->getValue('enable_geoloc') )
        ->save();
   
    $this->entityTypeManager->getViewBuilder('block')->resetCache();

    parent::submitForm($form, $form_state);

  }


  public function updateFieldAddress(array &$form, FormStateInterface $form_state){
    
    $apiKey         = $this->configCr->get('api_key');
    $address        = $form_state->getValue('address');
    $verifyAddress  = $this->verifyAddress($apiKey,$address);

    $isAddressValid = $this->verifyAddressValidaty($verifyAddress);

    if ( $isAddressValid ) {

      $location = $verifyAddress->results[0]->geometry->location;// ->lat ou ->lng
      $lat      = $location->lat;
      $lng      = $location->lng;

      $form['coordinate'] = array(
        '#type'         => 'fieldset',
        '#prefix'       => '<div id="coordinate">',
        '#suffix'       => '</div>',
        '#title'        => $this->t('Default coordinate'),
        '#description'  => '<a href="https://www.coordonnees-gps.fr/">'.$this->t('Récupérer les coordonnées GPS').'</a></br><a href="https://www.gps-coordinates.net/">'.$this->t('Get GPS Coordonninates').'</a>',
      );

      $form['coordinate']['address'] = [
        '#type'           =>  'textfield',
        //'#autocomplete_path' => 'node_reference/autocomplete/node/link/field_contact_reference',
        '#title'          =>  $this->t('Address'),
        '#size'           =>  40,
        '#id'             => "edit-address",
        '#name'           => "address",
        '#value'          =>  $address,
        '#description'    =>  'Entering an address allows you to automatically fill in the coordinate fields',
        '#ajax'           =>  array(
                                'callback'  => array($this,'updateFieldAddress'),
                                'event'     => 'change',
                                'wrapper' => 'coordinate',
                                'method' => 'replace',
                                'effect' => 'fade',
                              ),
      ];
      $form['coordinate']['latitude'] = array(
        '#type'           => 'textfield',
        '#title'          => $this->t('Latitude'),
        '#size'           => 23,
        '#value'          => $lat,
        '#id'             => "edit-latitude",
        '#name'           => "latitude"
      );

      $form['coordinate']['longitude'] = array(
        '#type'           => 'textfield',
        '#title'          => $this->t('Longitude'),
        '#type'           => 'textfield',
        '#size'           => 23,
        '#value'          => $lng,
        '#id'             => "edit-longitude",
        '#name'           => "longitude"
      );

      return $form['coordinate'];
    }
    else{
      return false;
    }

  }


  public function verifyAddress($apiKey,$address){

      $address = urlencode ( $address );
      //$address =  str_replace ( " " , "+" , $address );
      $urlToTest  = "https://maps.googleapis.com/maps/api/geocode/json?key=".$apiKey."&address=".$address;

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $urlToTest);
      $result = curl_exec($ch);
      curl_close($ch);

      return json_decode($result);

  }


  public function verifyAddressValidaty($testAddress){
    if ($testAddress->status === "OK") {
      return true;
    }
    return false;
  }




}
