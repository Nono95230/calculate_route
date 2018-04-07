<?php

namespace Drupal\calculate_route\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactory;

/**
 * Class AppearenceForm.
 */
class AppearenceForm extends ConfigFormBase {


  protected $entityTM;
  protected $configCr;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, ConfigFactory $config){
    $this->entityTM  = $entityTypeManager;
    $this->configCr  = $config->getEditable("calculate_route.config");
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
    return '__appearence';
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

    $form['map'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Map Settings'),
    );
    $form['form'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Form Settings'),
    );
    $form['map']['dimension'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Dimensions'),
    );

    $form['form']['position'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Position management'),
    );

    $form['form']['color'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Color management'),
    );

    $form['form']['color']['text'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Text'),
    );

    $form['form']['color']['bg'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Background'),
    );

    $form['form']['color']['three_btn'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('BTN switch & BTN Minimize Form & BTN Restore Form '),
    );

    $form['map']['dimension']['width_map'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Width'),
      '#size' => 15,
      '#default_value' => $this->configCr->get('appearence.width_map')
    );

    $form['map']['dimension']['height_map'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Height'),
      '#size' => 15,
      '#default_value' => $this->configCr->get('appearence.height_map')
    );

    $form['form']['position']['top_position'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Top position'),
      '#size' => 15,
      '#default_value' => $this->configCr->get('appearence.top_position')
    );

    $form['form']['position']['bottom_position'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Bottom position'),
      '#size' => 15,
      '#default_value' => $this->configCr->get('appearence.bottom_position')
    );

    $form['form']['position']['left_position'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Left position'),
      '#size' => 15,
      '#default_value' => $this->configCr->get('appearence.left_position')
    );

    $form['form']['position']['right_position'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Right position'),
      '#size' => 15,
      '#default_value' => $this->configCr->get('appearence.right_position')
    );


    $form['form']['color']['text']['label_text_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Text Color for Label Start & End'),
      '#default_value' => $this->configCr->get('appearence.label_text_color')
    );

    $form['form']['color']['text']['button_text_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Text Color for Submit Button'),
      '#default_value' => $this->configCr->get('appearence.button_text_color')
    );
    
    /* Le champs ci dessous a été édité car avant il créait des bugs, cela était incompréhensible */
    $form['form']['color']['bg']['head_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Head Form Color'),
      '#default_value' => $this->configCr->get('appearence.header_color')
    );

    $form['form']['color']['bg']['form_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Form Color'),
      '#default_value' => $this->configCr->get('appearence.form_color')
    );

    $form['form']['color']['bg']['button_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Button Color'),
      '#default_value' => $this->configCr->get('appearence.button_color')
    );

    $form['form']['color']['three_btn']['three_btn_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Button Color'),
      '#default_value' => $this->configCr->get('appearence.three_btn_color')
    );

    $form['form']['color']['three_btn']['three_btn_hover_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Button Hover Color'),
      '#default_value' => $this->configCr->get('appearence.three_btn_hover_color')
    );



    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->configCr
      ->set( 'appearence.width_map', $form_state->getValue('width_map') )
      ->set( 'appearence.height_map', $form_state->getValue('height_map') )
      ->set( 'appearence.label_text_color', $form_state->getValue('label_text_color') )
/*      ->set( 'appearence.button_text_color', $form_state->getValue('button_text_color') )*/
      ->set( 'appearence.header_color', $form_state->getValue('head_color') )
      ->set( 'appearence.form_color', $form_state->getValue('form_color') )
      ->set( 'appearence.button_color', $form_state->getValue('button_color') )
      ->set( 'appearence.three_btn_color', $form_state->getValue('three_btn_color') )
      ->set( 'appearence.three_btn_hover_color', $form_state->getValue('three_btn_hover_color') )
      ->set( 'appearence.top_position', $form_state->getValue('top_position') )
      ->set( 'appearence.bottom_position', $form_state->getValue('bottom_position') )
      ->set( 'appearence.left_position', $form_state->getValue('left_position') )
      ->set( 'appearence.right_position', $form_state->getValue('right_position') )
      ->save();

    $this->entityTM->getViewBuilder('block')->resetCache();

    parent::submitForm($form, $form_state);

  }

}
