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
  protected $unity;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, ConfigFactory $config){
    $this->entityTM   = $entityTypeManager;
    $this->configCr   = $config->getEditable("calculate_route.config");
    $this->unity      = $this->setUnity($this->configCr->get('config.unity'));
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
    return 'settings__appearence';
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

    $form['settings_appearence'] = array(
      '#type' => 'vertical_tabs',
      '#default_tab' => 'edit-dimension-map',
      '#attached' => array(
        'library' => array(
          'calculate_route/appearence_v-tabs'
        )
      )
    );


    $form['dimension-map'] = array(
      '#type' => 'details',
      '#title' => $this->t('Map Dimension'),
      '#group' => 'settings_appearence',
    );


    $form['form-position'] = array(
      '#type' => 'details',
      '#title' => $this->t('Form Settings : Position management'),
      '#group' => 'settings_appearence',
    );


    $form['form-color-text'] = array(
      '#type' => 'details',
      '#title' => $this->t('Form Color Settings : Text'),
      '#group' => 'settings_appearence',
    );


    $form['form-color-bg'] = array(
      '#type' => 'details',
      '#title' => $this->t('Form Color Settings : Background'),
      '#group' => 'settings_appearence',
    );


    $form['form-color-three_btn'] = array(
      '#type' => 'details',
      '#title' => $this->t('Form Color Settings : BTN switch & BTN Minimize Form & BTN Restore Form '),
      '#group' => 'settings_appearence',
    );


    // Generating fields related to the size of the map
    $inputGroup = array(
      'details' => 'dimension-map',
      'fieldgroup' => array(
        'name' => 'width',
        'title' => 'Width'
      ),
      'textfield' => array(
        'name' => 'width_map',
        'size' => 3,
        'attributes' => array(
          'data-property' => 'width',
          'data-selector' => '#container_map',
          'data-jquery-method' => 'css'
        )
      ),
      'select_exceptions' => 'auto'
    );
    $this->setInputGroup($form, $inputGroup);


    // $form['dimension-map']['width'] = array(
    //   '#type' => 'fieldgroup',
    //   '#title' => $this->t('Width'),
    // );


    // $form['dimension-map']['width']['width_map'] = array(
    //   '#type' => 'textfield',
    //   '#size' => 3,
    //   '#attributes' => array(
    //       'data-property' => 'width',
    //       'data-selector' => '#container_map',
    //       'data-jquery-method' => 'css'
    //   ),
    //   '#default_value' => $this->getMesure($this->configCr->get('appearence.width_map'))
    // );


    // $form['dimension-map']['width']['width_map_unity'] = [
    //   '#type' => 'select',
    //   '#options' => $this->removeUnity($this->unity, 'auto'),
    //   '#attributes' => array(
    //       'data-property' => 'width',
    //       'data-selector' => '#container_map',
    //       'data-jquery-method' => 'css'
    //   ),
    //   '#default_value' => $this->getUnity($this->configCr->get('appearence.width_map'))
    // ];


    $form['dimension-map']['height'] = array(
      '#type' => 'fieldgroup',
      '#title' => $this->t('Height'),
    );


    $form['dimension-map']['height']['height_map'] = array(
      '#type' => 'textfield',
      '#size' => 3,
      '#attributes' => array(
          'data-property' => 'height',
          'data-selector' => '#container_map',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->getMesure($this->configCr->get('appearence.height_map'))
    );


    $form['dimension-map']['height']['height_map_unity'] = [
      '#type' => 'select',
      '#options' => $this->removeUnity($this->unity, 'auto'),
      '#attributes' => array(
          'data-property' => 'height',
          'data-selector' => '#container_map',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->getUnity($this->configCr->get('appearence.height_map'))
    ];

    $form['form-position']['top'] = array(
      '#type' => 'fieldgroup',
      '#title' => $this->t('Top position'),
    );


    $form['form-position']['top']['top_position'] = array(
      '#type' => 'textfield',
      '#size' => 3,
      '#attributes' => array(
          'data-property' => 'top',
          'data-selector' => '#container_form',
          'data-jquery-method' => 'css'
      ),
      '#states' => array(
        'invisible' => array(
          'select[name="top_position_unity"]' => array('value' => "auto")
        ),
      ),
      '#default_value' => $this->getMesure($this->configCr->get('appearence.top_position'))
    );


    $form['form-position']['top']['top_position_unity'] = [
      '#type' => 'select',
      '#options' => $this->unity,
      '#attributes' => array(
          'data-property' => 'top',
          'data-selector' => '#container_form',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->getUnity($this->configCr->get('appearence.top_position'))
    ];


    $form['form-position']['bottom'] = array(
      '#type' => 'fieldgroup',
      '#title' => $this->t('Bottom position'),
    );


    $form['form-position']['bottom']['bottom_position'] = array(
      '#type' => 'textfield',
      '#size' => 3,
      '#attributes' => array(
          'data-property' => 'bottom',
          'data-selector' => '#container_form',
          'data-jquery-method' => 'css'
      ),
      '#states' => array(
        'invisible' => array(
          'select[name="bottom_position_unity"]' => array('value' => "auto")
        ),
      ),
      '#default_value' => $this->getMesure($this->configCr->get('appearence.bottom_position'))
    );


    $form['form-position']['bottom']['bottom_position_unity'] = [
      '#type' => 'select',
      '#options' => $this->unity,
      '#attributes' => array(
          'data-property' => 'bottom',
          'data-selector' => '#container_form',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->getUnity($this->configCr->get('appearence.bottom_position'))
    ];


    $form['form-position']['left'] = array(
      '#type' => 'fieldgroup',
      '#title' => $this->t('Left position'),
    );


    $form['form-position']['left']['left_position'] = array(
      '#type' => 'textfield',
      '#size' => 3,
      '#attributes' => array(
          'data-property' => 'left',
          'data-selector' => '#container_form',
          'data-jquery-method' => 'css'
      ),
      '#states' => array(
        'invisible' => array(
          'select[name="left_position_unity"]' => array('value' => "auto")
        ),
      ),
      '#default_value' => $this->getMesure($this->configCr->get('appearence.left_position'))
    );


    $form['form-position']['left']['left_position_unity'] = [
      '#type' => 'select',
      '#options' => $this->unity,
      '#attributes' => array(
          'data-property' => 'left',
          'data-selector' => '#container_form',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->getUnity($this->configCr->get('appearence.left_position'))
    ];


    $form['form-position']['right'] = array(
      '#type' => 'fieldgroup',
      '#title' => $this->t('Right position'),
    );


    $form['form-position']['right']['right_position'] = array(
      '#type' => 'textfield',
      '#size' => 3,
      '#attributes' => array(
          'data-property' => 'right',
          'data-selector' => '#container_form',
          'data-jquery-method' => 'css'
      ),
      '#states' => array(
        'invisible' => array(
          'select[name="right_position_unity"]' => array('value' => "auto")
        ),
      ),
      '#default_value' => $this->getMesure($this->configCr->get('appearence.right_position'))
    );


    $form['form-position']['right']['right_position_unity'] = [
      '#type' => 'select',
      '#options' => $this->unity,
      '#attributes' => array(
          'data-property' => 'right',
          'data-selector' => '#container_form',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->getUnity( $this->configCr->get('appearence.right_position') )
    ];


    $form['form-color-text']['label_text_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Text Color for Label Start & End'),
      '#attributes' => array(
          'data-property' => 'color',
          'data-selector' => '.label_style',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->configCr->get('appearence.label_text_color')
    );


    $form['form-color-text']['button_text_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Text Color for Submit Button'),
      '#attributes' => array(
          'data-property' => 'color',
          'data-selector' => '#label_btn',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->configCr->get('appearence.button_text_color')
    );


    $form['form-color-bg']['head_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Head Form Color'),
      '#attributes' => array(
          'data-property' => 'background-color',
          'data-selector' => '#choice_mode',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->configCr->get('appearence.header_color')
    );


    $form['form-color-bg']['form_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Form Color'),
      '#attributes' => array(
          'data-property' => 'background-color',
          'data-selector' => '#container_form',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->configCr->get('appearence.form_color')
    );


    $form['form-color-bg']['button_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Button Color'),
      '#attributes' => array(
          'data-property' => 'background-color',
          'data-selector' => '#label_btn',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->configCr->get('appearence.button_color')
    );

    $form['form-color-three_btn']['three_btn_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Button Color'),
      '#attributes' => array(
          'data-property' => 'color',
          'data-selector' => '.btn-con, .icon-fa',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->configCr->get('appearence.three_btn_color')
    );


    $form['form-color-three_btn']['three_btn_hover_color'] = array(
      '#type' => 'jquery_colorpicker',
      '#title' => $this->t('Button Hover Color'),
      '#attributes' => array(
          'data-property' => 'color',
          'data-selector' => '.btn-icon, .icon-fa',
          'data-jquery-method' => 'css'
      ),
      '#default_value' => $this->configCr->get('appearence.three_btn_hover_color')
    );


    // kint($form_state);
    // die;

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

    kint($form);
    kint($form_state);
    die;

    $this->configCr
      ->set( 'appearence.width_map', $this->setMesure($form_state, 'width_map') )
      ->set( 'appearence.height_map', $this->setMesure($form_state, 'height_map') )
      ->set( 'appearence.top_position', $this->setMesure($form_state, 'top_position') )
      ->set( 'appearence.bottom_position', $this->setMesure($form_state, 'bottom_position') )
      ->set( 'appearence.left_position', $this->setMesure($form_state, 'left_position'))
      ->set( 'appearence.right_position', $this->setMesure($form_state, 'right_position') )
      ->set( 'appearence.label_text_color', $form_state->getValue('label_text_color') )
      ->set( 'appearence.button_text_color', $form_state->getValue('button_text_color') )
      ->set( 'appearence.header_color', $form_state->getValue('head_color') )
      ->set( 'appearence.form_color', $form_state->getValue('form_color') )
      ->set( 'appearence.button_color', $form_state->getValue('button_color') )
      ->set( 'appearence.three_btn_color', $form_state->getValue('three_btn_color') )
      ->set( 'appearence.three_btn_hover_color', $form_state->getValue('three_btn_hover_color') )
      ->save();

    $this->entityTM->getViewBuilder('block')->resetCache();

    parent::submitForm($form, $form_state);

  }

    /**
     * @param array &$form
     * @param array $inputGroup
     * must contain:
     * (array){
     *   details => (string),
     *   fieldgroup => (array){
     *     name => (string),
     *     title => (string)
     *   },
     *   textfield => (array){
     *     name => (string),
     *     size => (integer),
     *     attributes => (array){
     *       property => (string),
     *       selector => (string),
     *       jquery-method => (string)
     *     },
     *   },
     *   select_exceptions => (string)
     * }
     */
    public function setInputGroup(array &$form, array $inputGroup){

      $form[$inputGroup['details']][$inputGroup['fieldgroup']['name']] = array(
        '#type' => 'fieldgroup',
        '#title' => $this->t($inputGroup['fieldgroup']['title']),
      );

      $form[$inputGroup['details']][$inputGroup['fieldgroup']['name']][$inputGroup['textfield']['name']] = array(
        '#type' => 'textfield',
        '#size' => $inputGroup['textfield']['size'],
        '#attributes' => $inputGroup['textfield']['attributes'],
        '#default_value' => $this->getMesure($this->configCr->get('appearence.'.$inputGroup['textfield']['name']))
      );


      $form[$inputGroup['details']][$inputGroup['fieldgroup']['name']][$inputGroup['textfield']['name'].'_unity'] = [
        '#type' => 'select',
        '#options' => $this->removeUnity($this->unity, 'auto'),
        '#attributes' => $inputGroup['textfield']['attributes'],
        '#default_value' => $this->getUnity($this->configCr->get('appearence.'.$inputGroup['textfield']['name']))
      ];

      $selectOptions = ( $inputGroup['select_exceptions'] == 'none' )? $this->unity : $this->removeUnity($this->unity, $inputGroup['select_exceptions']);

      $form[$inputGroup['details']][$inputGroup['fieldgroup']['name']][$inputGroup['textfield']['name'].'_unity']['#options'] = $selectOptions;

    }

  public function setUnity($unities){
    $newArray = [];
    foreach ($unities as $unity) {
      $newArray[$unity] = $unity;
    }
    return $newArray;
  }

  public function removeUnity($array, $remove){
    unset($array[$remove]);
    return $array;
  }

  public function getMesure($mesure){
    $unities = $this->configCr->get('config.unity');
    foreach ($unities as $unity) {
      if( false !== strpos($mesure,'auto') ){
        return '';
      }
      if( false !== strpos($mesure, $unity) ){
        return str_replace($unity, "", $mesure);
      }
    }
  }

  public function getUnity($mesure){
    $unities = $this->configCr->get('config.unity');
    foreach ($unities as $unity) {
      if( false !== strpos($mesure, $unity) ){
        return  $unity;
      }
    }
  }

  public function setMesure($form_state, $inputName){

    $mesure = $form_state->getValue($inputName);
    $unity = $form_state->getValue($inputName.'_unity');

    switch ($unity) {
      case 'auto':
        $response = $unity;
        break;
      default:
        $response = $mesure.$unity;
        break;
    }

    return $response;

  }


}
