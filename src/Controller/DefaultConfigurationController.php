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


	protected $entityTM;
	protected $originConfigCr;
	protected $currentConfigCr;


	public function __construct(EntityTypeManagerInterface $entityTypeManager, ConfigFactory $config){
		$pathFileConfig 		= drupal_get_path("module", "calculate_route")."/config/install/calculate_route.config.yml";
		$fileConfig 			= file_get_contents($pathFileConfig);
		$this->originConfigCr 	= Yaml::parse($fileConfig);
		$this->entityTM 		= $entityTypeManager;
		$this->currentConfigCr 	= $config->getEditable("calculate_route.config");
	}


	public static function create(ContainerInterface $container){
		return new static(
			$container->get("entity_type.manager"),
			$container->get("config.factory")
		);
	}


	public function map(){
		return $this->backDefaultConfig("map");
	}


	public function marker(){
		return $this->backDefaultConfig("marker");
	}


	public function form(){
		return $this->backDefaultConfig("form");
	}


	public function appearence(){
		return $this->backDefaultConfig("appearence");
	}


	public function backDefaultConfig($param){
	    $this->currentConfigCr
		    ->set( $param, $this->originConfigCr[$param] )
		    ->save();

 		$this->entityTM->getViewBuilder("block")->resetCache();

		return new RedirectResponse(\Drupal::url( "calculate_route.config.".$param ));
	}

}
