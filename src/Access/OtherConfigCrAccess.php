<?php

namespace Drupal\calculate_route\Access;

use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Access\AccessResult;



class OtherConfigCrAccess implements AccessCheckInterface{


	public function applies(Route $route){

		return NULL;

	}

	public function access(Route $route,Request $request = NULL, AccountInterface $account ){

		//$param = $route->getRequirement('_access_gm_api_key_is_valid');

		$apiKeyisValid = ( \Drupal::config('calculate_route.config')->get('api_key_is_valid') === 1 );

		if ($apiKeyisValid) {
			return AccessResult::allowed();
		} else {
			return AccessResult::forbidden();
		}

	}





	

}
