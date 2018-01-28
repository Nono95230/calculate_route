<?php

namespace Drupal\calculate_route\Controller;

use Drupal\Core\Controller\ControllerBase;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Entity\EntityTypeManagerInterface;

use Drupal\Core\Config\ConfigFactory;
use Symfony\Component\Yaml\Yaml;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;


class DefaultConfigurationController extends ControllerBase{


	protected $entityTypeManager;
	protected $configCr;
	protected $originalConfigCr;


	public function __construct(EntityTypeManagerInterface $entityTypeManager, ConfigFactory $config){
		$this->entityTypeManager = $entityTypeManager;
		$this->configCr 		 = $config->getEditable("calculate_route.config");

		$path = drupal_get_path("module", "calculate_route");
		$file = file_get_contents($path ."/config/install/calculate_route.config.yml");

		$this->originalConfigCr  = Yaml::parse($file);


	}


	public static function create(ContainerInterface $container){
		return new static(
			$container->get('entity_type.manager'),
			$container->get('config.factory')
		);
	}


	public function map(){

	    $this->configCr
	        ->set( 'map', $this->originalConfigCr['map'] )
	        ->save();

 		$this->entityTypeManager->getViewBuilder('block')->resetCache();
		return new RedirectResponse(\Drupal::url('calculate_route.config.map'));

	}


	public function marker(){

	    $this->configCr
	        ->set( 'marker', $this->originalConfigCr['marker'] )
	        ->save();

 		$this->entityTypeManager->getViewBuilder('block')->resetCache();
		return new RedirectResponse(\Drupal::url('calculate_route.config.marker'));

	}


	public function form(){

	    $this->configCr
	        ->set( 'form', $this->originalConfigCr['form'] )
	        ->save();

 		$this->entityTypeManager->getViewBuilder('block')->resetCache();
		return new RedirectResponse(\Drupal::url('calculate_route.config.form'));

	}


	public function appearence(){

	    $this->configCr
	        ->set( 'appearence', $this->originalConfigCr['appearence'] )
	        ->save();

 		$this->entityTypeManager->getViewBuilder('block')->resetCache();
		return new RedirectResponse(\Drupal::url('calculate_route.config.appearence'));

	}


}
