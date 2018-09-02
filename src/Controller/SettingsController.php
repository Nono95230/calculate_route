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




    //kint($this->mapping);
    //kint($this->routeFile);
    //kint($this->allRoutesName);
    //die;

    $url1 = \Drupal\Core\Url::fromRoute('calculate_route.settings');
    $url2 = \Drupal\Core\Url::fromRoute('calculate_route.config.map');

/*
    foreach ($variable as $key => $value) {
      # code...
    }*/


    $build[] = [
      '#theme'  => 'calculate_route_settings',
      '#data'   => [
        'panelTitle'  => t('Calculate Route Settings'),
        'listSettings' => [
          [
            'title' => t('Api Key'),
            'link' => '/admin/config/services/calculate-route/config/api-key',
            'description' => t('Api Key Description.')
          ],
          [
            'title' => t('Default Map.'),
            'link' => '/admin/config/services/calculate-route/config/map',
            'description' => t('Default Map Description.')
          ],
        ],
      ]
    ];

    return [$build];










  }



}
