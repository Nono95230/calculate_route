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

    $routeNotAllowed = 'calculate_route.settings';
    unset($this->mapping[$routeNotAllowed]);

    $listSettings = [];
    foreach ($this->mapping as $routeName => $routeContent) {
      $listSettings[] = [
        'title' => t($routeContent['title']),
        'link' => Url::fromRoute($routeName)->toString(),
        'description' => t($routeContent['description'])
      ];
    }

    $build[] = [
      '#theme'  => 'calculate_route_settings',
      '#data'   => [
        'panelTitle'  => t('Google Maps Settings'),
        'listSettings' => $listSettings,
      ]
    ];

    return [$build];

  }

}
