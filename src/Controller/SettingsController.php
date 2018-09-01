<?php

namespace Drupal\calculate_route\Controller;

use Drupal\Core\Controller\ControllerBase;



use Symfony\Component\Yaml\Yaml;

use Drupal\Core\Url;


class SettingsController extends ControllerBase{

  protected $mapping;

  protected $routeFile;

  protected $allRoutesName;


  public function __construct(){
    $this->mapping = Yaml::parse(
      file_get_contents(
        drupal_get_path('module', 'calculate_route') .
        '/mapping/mapping.settings.yml'
      )
    );

    $this->routeFile = Yaml::parse(
      file_get_contents(
        drupal_get_path('module', 'calculate_route') .
        '/calculate_route.routing.yml'
      )
    );
    $this->allRoutesName = array_keys($this->routeFile);
  }



  public function list(){




    kint($this->mapping);
    kint($this->routeFile);
    kint($this->allRoutesName);
    //die;
/*
    $url1 = \Drupal\Core\Url::fromRoute('book.admin');
    $url2 = \Drupal\Core\Url::fromRoute('book.admin');

    $build['item_list'] = [
      '#theme' => 'item_list',
      '#list_type' => 'ul',
      '#wrapper_attributes' => [
        'class' => [
          'wrapper',
        ],
      ],
      '#attributes' => [
        'class' => [
          'wrapper__links',
        ],
      ],
      '#items' => [
        [
          '#markup' => \Drupal::l(t('Url 1'), $url1),
          '#wrapper_attributes' => [
            'class' => [
              'wrapper__links__link',
            ],
          ],
        ],
        [
          '#markup' => \Drupal::l(t('Url 2'), $url2),
          '#wrapper_attributes' => [
            'class' => [
              'wrapper__links__link',
            ],
          ],
        ],
      ],
    ];

    return $build;
*/









  }



}
