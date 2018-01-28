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

    $form['coordinate'] = array(
      '#type'         => 'fieldset',
      '#title'        => $this->t('Default coordinate'),
      '#description'  => '<a href="https://www.coordonnees-gps.fr/">'.$this->t('Récupérer les coordonnées GPS').'</a></br><a href="https://www.gps-coordinates.net/">'.$this->t('Get GPS Coordonninates').'</a>',
    );

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

    $this->configCr
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

}
