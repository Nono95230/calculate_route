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

    $form['map_center']['address'] = [
      '#type'           =>  'textfield',
      '#title'          =>  $this->t('Address'),
      '#size'           =>  80,
      '#default_value'  =>  $this->configCr->get('map.address'),
      '#description'    =>  $this->t('Entering an address allows you to automatically fill in the coordinate fields'),
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

    $apiKey     = $this->configCr->get('api_key');
    $oldAddress = $this->configCr->get('map.address');
    $newAddress = $form_state->getValue('address');

    // If Address change
    if($oldAddress !== $newAddress){

      $getAddress       = $this->getAddress($apiKey,$newAddress);
      $isAddressValid   = $this->verifyAddressValidaty($getAddress);

      if ( $isAddressValid ) {
        $location = $getAddress->results[0]->geometry->location;

        $this->configCr
            ->set( 'map.address', $newAddress )
            ->set( 'map.latitude', $location->lat )
            ->set( 'map.longitude', $location->lng )
            ->save();

      }
      
    }

    $this->configCr
        ->set( 'map.zoom', $form_state->getValue('zoom') )
        ->set( 'map.zoom_max', $form_state->getValue('zoom_max') )
        ->set( 'map.zoom_scroll', $form_state->getValue('zoom_scroll') )
        ->set( 'map.map_type', $form_state->getValue('map_type') )
        ->set( 'map.enable_geoloc', $form_state->getValue('enable_geoloc') )
        ->save();
   
    $this->entityTypeManager->getViewBuilder('block')->resetCache();

    parent::submitForm($form, $form_state);

  }



  public function getAddress($apiKey,$address){

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
