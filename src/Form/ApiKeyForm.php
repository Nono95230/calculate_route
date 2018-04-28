<?php

namespace Drupal\calculate_route\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Asset\LibraryDiscovery;
use Drupal\Core\Config\ConfigFactory;


/**
 * Class ApiKeyForm.
 */
class ApiKeyForm extends ConfigFormBase {

  protected $entityTypeManager;
  protected $library;
  protected $configCr;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, LibraryDiscovery $library, ConfigFactory $config){
    $this->entityTypeManager  = $entityTypeManager;
    $this->library            = $library;
    $this->configCr           = $config->getEditable("calculate_route.config");
  }

  public static function create(ContainerInterface $container){
    return new static(
      $container->get('entity_type.manager'),
      $container->get('library.discovery'),
      $container->get('config.factory')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return '__api_key';
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
    
    $form['api_key'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Google Maps API KEY'),
      '#default_value' => $this->configCr->get('api_key'),
      '#attributes' => array(
        'class'=> array('gm-api-key')
      ),
      '#description'=> '<a href="https://developers.google.com/maps/documentation/embed/get-api-key">'.$this->t('Get your Google Maps API key').'</a>',
    
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

    $oldApiKEy = $this->configCr->get('api_key');
    $newApiKey = $form_state->getValue('api_key');

    // If Api Key change
    if($oldApiKEy !== $newApiKey){


      // START - Save the new Api Key
      $this->configCr
          ->set( 'api_key', $newApiKey )
          ->save();
      // END - Save the new Api Key


      // START - Test if Api Key is Valid
      $verifyApiKey = $this->verifyGoogleMapApiKey($newApiKey);

      if ( $verifyApiKey->status === "REQUEST_DENIED" ) {
        if ( isset($verifyApiKey->error_message) && $verifyApiKey->error_message === "The provided API key is invalid." ) {
          $this->configCr
              ->set( 'api_key_is_valid', 0 )
              ->save();
        }
      }elseif($verifyApiKey->status === "OK"){
        $this->configCr
            ->set( 'api_key_is_valid', 1 )
            ->save();
      }
      // END - Test if Api Key is Valid


      // START - Clear Cache
      $this->library->clearCachedDefinitions();
      $this->entityTypeManager->getViewBuilder('block')->resetCache();
      // END - Clear Cache
      
    }

    parent::submitForm($form, $form_state);

  }



  public function verifyGoogleMapApiKey($apiKey){

      $urlToTest  = "https://maps.googleapis.com/maps/api/geocode/json?key=".$apiKey."&address=550+King+St+N,+Waterloo,+ON+Canada";
       
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $urlToTest);
      $result = curl_exec($ch);
      curl_close($ch);

      return json_decode($result);

  }


}
