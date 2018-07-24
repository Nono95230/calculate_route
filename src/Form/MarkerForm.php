<?php

namespace Drupal\calculate_route\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactory;


/**
 * Class MarkerForm.
 */
class MarkerForm extends ConfigFormBase {

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
    return 'settings__marker';
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

    $form['settings_marker'] = array(
      '#type'         => 'vertical_tabs',
      '#default_tab'  => 'edit-marker-position',
      '#attached'     => array(
                          'library' => array(
                            'calculate_route/marker_v-tabs'
                          )
                        )
    );

    $form['marker_position'] = array(
      '#type'           => 'details',
      '#title'          => $this->t('Default Marker Position'),
      '#group'          => 'settings_marker',
    );


    $form['info_text'] = array(
      '#type'           => 'details',
      '#title'          => $this->t('Default Text'),
      '#group'          => 'settings_marker',
    );


    $form['marker_position']['address_or_coordinate'] = array(
      '#type'           => 'radios',
      '#title'          => $this->t('Set the default marker position with a'),
      '#default_value'  => $this->configCr->get('marker.address_or_coordinate'),
      '#options'        => array(
                          "address"     => $this->t('Physic Address'),
                          "coordinates" => $this->t('Coordinate (Latitude/Longitude)'),
                        ),
      '#description'    => '<h6>'.$this->t('Vous pouvez choisir la position du marqueur avec une adresse ou des coordonnées géographique !').'</h6>',
    );



    $form['marker_position']['address'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Address'),
      '#size'           => 80,
      '#prefix'         => '<div id="marker_settings_address">',
      '#suffix'         => '</div>',
      '#default_value'  => $this->configCr->get('marker.address'),
      '#states'         => array(
                          'visible' => array(
                            'input[name="address_or_coordinate"]' => array('value' => "address")
                          ),
                        ),
    ];
    $form['marker_position']['latitude'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Latitude'),
      '#default_value'  => $this->configCr->get('marker.latitude'),
      '#states'         => array(
                          'visible' => array(
                            'input[name="address_or_coordinate"]' => array('value' => "coordinates")
                          ),
                        ),
    ];


    $form['marker_position']['longitude'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Longitude'),
      '#default_value'  => $this->configCr->get('marker.longitude'),
      '#states'         => array(
                          'visible' => array(
                            'input[name="address_or_coordinate"]' => array('value' => "coordinates")
                          ),
                        ),
    ];


    $form['info_text']['title'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Marker Title'),
      '#default_value'  => $this->configCr->get('marker.title')
    );

    $form['info_text']['enable_info_window'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Enable Info Window'),
    );

    if ($this->configCr->get('marker.enable_info_window') == 1) {
      $form['info_text']['enable_info_window']['#attributes'] = array('checked' => 'checked');
    }

    $form['info_text']['info_window'] = array(
      '#type'           => 'text_format',
      '#title'          => t('Info Window'),
      '#format'         => 'full_html',
      '#states'         => array(
                          'visible' => array(
                            'input[name="enable_info_window"]' => array('checked' => TRUE)
                          ),
                        ),
      '#default_value'  => $this->configCr->get('marker.info_window')
    );


    return parent::buildForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $configName = str_replace("settings__","",$this->getFormId());

    $this->saveOtherConfigValue($form_state, $configName,
    [
      'address_or_coordinate',
      'info_window',
      'title',
      'enable_info_window',
    ]);

    $config = array($configName);

    $this->saveLocationMap($form_state, $config);

    $this->entityTypeManager->getViewBuilder('block')->resetCache();

    parent::submitForm($form, $form_state);

  }


  public function saveOtherConfigValue($form_state, $config, $otherConfigValue){
    for ($i=0; $i < count($otherConfigValue); $i++) {
      $old  = $this->configCr->get($config.'.'.$otherConfigValue[$i]);
      $_new = $form_state->getValue($otherConfigValue[$i]);
      $new  = (is_array ( $_new ) && array_key_exists('value', $_new) ? $_new['value'] : $_new );

      if ( $old !== $new ) {
        $this->configCr->set( $config.'.'.$otherConfigValue[$i] , $new);
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
