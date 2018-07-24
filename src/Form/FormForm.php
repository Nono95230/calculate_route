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
    return 'settings__form';
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

    $form['settings_form'] = array(
      '#type' => 'vertical_tabs',
      '#default_tab' => 'edit-address-destination',
      '#attached' => array(
        'library' => array(
          'calculate_route/form_v-tabs'
        )
      )
    );


    $form['address-destination'] = array(
      '#type'           => 'details',
      '#title'          => $this->t('Destination address'),
      '#group' => 'settings_form',
    );


    $form['enable-element'] = array(
      '#type'           => 'details',
      '#title'          => $this->t('Enable Element'),
      '#group' => 'settings_form',
    );


    $form['label-address'] = array(
      '#type'           => 'details',
      '#title'          => $this->t('Label address'),
      '#group' => 'settings_form',
    );


    $form['other-texts'] = array(
      '#type'           => 'details',
      '#title'          => $this->t('Other Texts'),
      '#group' => 'settings_form',
    );


    $form['address-destination']['title'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Address title'),
      '#size'           => 20,
      '#attributes' => array(
          'data-property' => 'html',
          'data-selector' => '#title'
      ),
      '#default_value'  => $this->configCr->get('form.title_address')
    );


    $form['address-destination']['address'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Address'),
      '#attributes' => array(
          'data-property' => 'val',
          'data-selector' => '#title'
      ),
      '#default_value'  => $this->configCr->get('form.address_destination')
    );


    $form['enable-element']['transport'] = array(
      '#type'           => 'checkboxes',
      '#options'        => array(
                          'car' => $this->t('Car'),
                          'public_transport' => $this->t('Public transport'),
                          'bike' => $this->t('Bike'),
                          'walker' => $this->t('Walker')
                        ),
      '#attributes' => array(
          'data-property' => 'checkboxes',
          'data-selector' => 'name'
      ),
      '#title'          => $this->t('Choose available transport modes'),
      '#default_value'  => $this->configCr->get('form.transport')
    );


    $form['enable-element']['btn_switch'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Enable switch button'),
      '#attributes' => array(
          'data-property' => 'checkbox',
          'data-selector' => '#switch'
      ),
      '#default_value'  => $this->configCr->get('form.btn_switch')
    );


    $form['enable-element']['btn_minimize_restore'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Enable minimize/restore form button'),
      '#attributes' => array(
          'data-property' => 'checkbox',
          'data-selector' => '#minimize_restore'
      ),
      '#default_value'  => $this->configCr->get('form.btn_minimize_restore')
    );


    $form['label-address']['sl_start'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Show start address label'),
      '#attributes' => array(
          'data-property' => 'checkbox',
          'data-selector' => '#label_start'
      ),
      '#default_value'  => $this->configCr->get('form.sl_start')
    );


    $form['label-address']['sl_end'] = array(
      '#type'           => 'checkbox',
      '#title'          => $this->t('Show end address label'),
      '#attributes' => array(
          'data-property' => 'checkbox',
          'data-selector' => '#label_end'
      ),
      '#default_value'  => $this->configCr->get('form.sl_end')
    );


    $form['label-address']['ct_start'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Customize text label for starting address'),
      '#states'         => array(
        'visible' => array(
          'input[name="sl_start"]' => array('checked' => TRUE)
        ),
      ),
      '#attributes' => array(
          'data-property' => 'html',
          'data-selector' => '#label_start'
      ),
      '#default_value'  => $this->configCr->get('form.ct_start')
    );


    $form['label-address']['ct_end'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Customize text label for ending address'),
      '#states'         => array(
        'visible' => array(
          'input[name="sl_end"]' => array('checked' => TRUE)
        ),
      ),
      '#attributes' => array(
          'data-property' => 'html',
          'data-selector' => '#label_end'
      ),
      '#default_value'  => $this->configCr->get('form.ct_end')
    );


    $form['other-texts']['ct_start_pl'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Customize text placeholder for starting address'),
      '#description'    => $this->t('Leave empty for no text'),
      '#attributes' => array(
          'data-property' => 'placeholder',
          'data-selector' => '#start'
      ),
      '#default_value'  => $this->configCr->get('form.ct_start_pl')
    );


    $form['other-texts']['ct_btn'] = array(
      '#type'           => 'textfield',
      '#title'          => $this->t('Customize text for submit button'),
      '#attributes' => array(
          'data-property' => 'html',
          'data-selector' => '#label_btn'
      ),
      '#default_value'  => $this->configCr->get('form.ct_btn')
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
        ->set( 'form.btn_switch', $form_state->getValue('btn_switch') )
        ->set( 'form.btn_minimize_restore', $form_state->getValue('btn_minimize_restore') )
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
