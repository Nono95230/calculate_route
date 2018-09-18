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
    $this->configCr = $config->getEditable("calculate_route.config");
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

    $fields = new FieldsGenerate('marker');

    $fields->generateForm($form);

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
