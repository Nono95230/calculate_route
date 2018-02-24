<?php

namespace Drupal\calculate_route\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Tags;
use Drupal\Component\Utility\Unicode;

/**
 * Defines a route controller for entity autocomplete form elements.
 */
class AddressAutocompleteController extends ControllerBase {

	/**
	* Handler for autocomplete request.
	*/
	public function handleAutocomplete(Request $request, $field_name, $count) {
		$results = [];

		// Get the typed string from the URL, if it exists.
		if ($input = $request->query->get('q')) {
			$typed_string = Tags::explode($input);
			$typed_string = Unicode::strtolower(array_pop($typed_string));
			// @todo: Apply logic for generating results based on typed_string and other
			// arguments passed.
			for ($i = 0; $i < $count; $i++) {
				$results[] = [
					'value' => $field_name . '_' . $i . '(' . $i . ')',
					'label' => $field_name . ' ' . $i,
				];
			}
		}
		/*$apiKey = \Drupal::config('calculate_route.config')->get('api_key_is_valid');
		$field_name = urlencode ( $field_name );
		
		$urlToTest  = "https://maps.googleapis.com/maps/api/place/queryautocomplete/json?key=".$apiKey."&input=".$field_name;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $urlToTest);
		$result = curl_exec($ch);
		curl_close($ch);

		return json_decode($result);*/
		//
		return new JsonResponse($results);
	}

}