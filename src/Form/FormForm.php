<?php

namespace Drupal\calculate_route\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactory;

/**
 * Class FormForm.
 */
class FormForm extends ConfigFormBase {

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
    return 'calculate_route_form';
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

    $form['transport'] = array(
      '#type'           => 'checkboxes',
      '#options'        => array(
                          'car' => $this->t('Car'),
                          'public_transport' => $this->t('Public transport'),
                          'bike' => $this->t('Bike'),
                          'walker' => $this->t('Walker')
                        ),
      '#title'          => $this->t('Choose available transport modes'),
      '#default_value'  => $this->configCr->get('form.transport')
    );


    $form['btn-switch'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Enable switch button'),
      '#default_value'  => $this->configCr->get('form.btn_switch')
    );


    $form['show-label-address'] = array(
      '#type'           => 'fieldset',
      '#title'          => $this->t('Hide label address'),
    );


    $form['customize-texts'] = array(
      '#type'           => 'fieldset',
      '#title'          => $this->t('Customize Texts'),
    );


    $form['address-destination'] = array(
      '#type'           => 'fieldset',
      '#title'          => $this->t('Destination address'),
    );
    

    $form['show-label-address']['sl_start'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Hide start address label'),
      '#default_value'  => $this->configCr->get('form.sl_start')
    );


    $form['show-label-address']['sl_end'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Hide end address label'),
      '#default_value'  => $this->configCr->get('form.sl_end')
    );


    $form['customize-texts']['ct_start'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Customize text label for starting address'),
      '#states'         => array(
                          'invisible' => array(
                            'input[name="sl_start"]' => array('checked' => TRUE)
                          ),
                        ),
      '#default_value'  => $this->configCr->get('form.ct_start')
    );


    $form['customize-texts']['ct_start_pl'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Customize text placeholder for starting address'),
      '#description'    => $this->t('Leave empty for no text'),
      '#default_value'  => $this->configCr->get('form.ct_start_pl')
    );


    $form['customize-texts']['ct_end'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Customize text label for ending address'),
      '#states'         => array(
                          'invisible' => array(
                            'input[name="sl_end"]' => array('checked' => TRUE)
                          ),
                        ),
      '#default_value'  => $this->configCr->get('form.ct_end')
    );


    $form['customize-texts']['ct_btn'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Customize text for submit button'),
      '#default_value'  => $this->configCr->get('form.ct_btn')
    );


    $form['address-destination']['title'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Address title'),
      '#size'           => 20,
      '#default_value'  => $this->configCr->get('form.title_address')
    );


    $form['address-destination']['address'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Address'),
      '#default_value'  => $this->configCr->get('form.address_destination')
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
        ->set( 'form.transport', $form_state->getValue('transport') )
        ->set( 'form.btn_switch', $form_state->getValue('btn-switch') )
        ->set( 'form.ct_start_pl', $form_state->getValue('ct_start_pl') )
        ->set( 'form.ct_start', $form_state->getValue('ct_start') )
        ->set( 'form.ct_end', $form_state->getValue('ct_end') )
        ->set( 'form.ct_btn', $form_state->getValue('ct_btn') )
        ->set( 'form.sl_start', $form_state->getValue('sl_start') )
        ->set( 'form.sl_end', $form_state->getValue('sl_end') )
        ->set( 'form.title_address', $form_state->getValue('title') )
        ->set( 'form.address_destination', $form_state->getValue('address') )
        ->save();

    $this->entityTypeManager->getViewBuilder('block')->resetCache();

    parent::submitForm($form, $form_state);

  }

}
