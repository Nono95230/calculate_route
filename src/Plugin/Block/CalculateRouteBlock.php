<?php

namespace Drupal\calculate_route\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'CalculateRoute' Block.
 *
 * @Block(
 *   id = "calculate_route_block",
 *   admin_label = @Translation("Calculate your route"),
 *   category = @Translation("Affiche une Google Map, et propose aux visiteurs de calculer un itinéraire"),
 * )
 */
class CalculateRouteBlock extends BlockBase {

  protected $configCr;

  public function __construct(){
    $this->configCr = \Drupal::config('calculate_route.config');
  }


  /**
   * {@inheritdoc}
   */
  public function build() {

    $phStart      = $this->configCr->get('form.ct_start_pl'); 
    $labelStart   = $this->configCr->get('form.ct_start'); 
    $labelEnd     = $this->configCr->get('form.ct_end'); 
    $textSubmit   = $this->configCr->get('form.ct_btn'); 
    $addressTitle = $this->configCr->get('form.title_address'); 
    $address      = $this->configCr->get('form.address_destination'); 
    $path         = "/".drupal_get_path("module", "calculate_route")."/images/";
    
    $info[] = array(
      '#theme'  => 'google_map',
      '#data'   => array(
                    "phStart"       => $phStart,
                    "labelStart"    => $labelStart,
                    "labelEnd"      => $labelEnd,
                    "textSubmit"    => $textSubmit,
                    "addressTitle"  => $addressTitle,
                    "address"       => $address,
                    "path"          => $path
                  )
    );

    return array($info);

  }
  
  /*protected function blockAccess(AccountInterface $account){
    return AccessResult::allowedIfHasPermission($account,'access hello block');
  }*/


}