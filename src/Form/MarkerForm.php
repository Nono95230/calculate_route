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
    return 'marker_form';
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
      '#type'           => 'fieldset',
      '#title'          => $this->t('Default coordinate'),
      '#description'    => '<a href="https://www.coordonnees-gps.fr/">'.$this->t('Récupérer les coordonnées GPS').'</a></br><a href="https://www.gps-coordinates.net/">'.$this->t('Get GPS Coordonninates').'</a>',
    );


    $form['info_text'] = array(
      '#type'           => 'fieldset',
      '#title'          => $this->t('Default Text'),
    );


    $form['coordinate']['latitude'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Latitude'),
      '#default_value'  => $this->configCr->get('marker.latitude')
    ];


    $form['coordinate']['longitude'] = [
      '#type'           => 'textfield',
      '#title'          => $this->t('Longitude'),
      '#default_value'  => $this->configCr->get('marker.longitude')
    ];

    
    $form['info_text']['title'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Marker Title'),
      '#default_value'  => $this->configCr->get('marker.title')
    );


    $form['info_text']['enable_info_window'] = array(
      '#type'           => 'select',
      '#title'          => $this->t('Enable Info Window'),
      '#options'        => array(
                          'true'  => $this->t('Enable'),
                          'false' => $this->t('Disable')
                        ),
      '#default_value'  => $this->configCr->get('marker.enable_info_window')
    );

    $form['info_text']['info_window'] = array(
      '#type'           => 'text_format',
      '#title'          => t('Info Window'),
      '#format'         => 'full_html',
      '#states'         => array(
                          'invisible' => array(
                            'select[name="enable_info_window"]' => array('value' => 'false')
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

    $this->configCr
        ->set( 'marker.latitude', $form_state->getValue('latitude') )
        ->set( 'marker.longitude', $form_state->getValue('longitude') )
        ->set( 'marker.title', $form_state->getValue('title') )
        ->set( 'marker.enable_info_window', $form_state->getValue('enable_info_window') )
        ->set( 'marker.info_window', $form_state->getValue('info_window')['value'] )
        ->save();
    
    $this->entityTypeManager->getViewBuilder('block')->resetCache();

    parent::submitForm($form, $form_state);

  }

}
