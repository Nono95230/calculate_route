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
    return 'api_key_form';
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

    if($oldApiKEy !== $newApiKey){

      $this->configCr
          ->set( 'api_key', $newApiKey )
          ->save();

      $this->library->clearCachedDefinitions();
      $this->entityTypeManager->getViewBuilder('block')->resetCache();
    }

    parent::submitForm($form, $form_state);

  }

}
