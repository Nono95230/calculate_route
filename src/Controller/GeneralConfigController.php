<?php

namespace Drupal\calculate_route\Controller;

use Drupal\Core\Controller\ControllerBase;


class GeneralConfigController extends ControllerBase{


	public function configPage(){
		$build =  array(
			'#markup' => 'Salut la page de config du module !'
		);
		return $build;
	}

}
