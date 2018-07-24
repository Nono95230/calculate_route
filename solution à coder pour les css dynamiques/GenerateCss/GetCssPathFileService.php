<?php

namespace Drupal\ausy_mb\Service;

use Drupal\Core\Entity\EntityInterface;

/**
 * Class GetCssPathFileService.
 *
 * @package Drupal\ausy_mb
 */
class GetCssPathFileService {

  /**
   * Récupère le chemin d'un fichier css.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Entitée en cours de manipulation.
   * @return array
   *   Le tableau de variables du chemin de fichier Css.
   */
  public static function getCssPathFile(EntityInterface $entity) {

    // Génération du chemin système
    // /var/www/html/clade/drupal/sites/default/files/css_mb
    $response['css-path-system'] = \Drupal::service('file_system')
        ->realpath(file_default_scheme() . "://") . '/css_mb';

    // Génération du chemin absolu
    $response['css-path-absolute'] = str_replace(
      \Drupal::service('file_system')->realpath(''),
      '',
      $response['css-path-system']
    );

    // Génération du nom de fichier.
    $response['file-name'] = mb_strtolower(str_replace(' ', '_', $entity->getTitle()));

    // Génération du chemin système vers le fichier.
    $response['file-path-system'] = $response['css-path-system'] . '/' . $response['file-name'] . '.php';

    // Génération du chemin absolu vers le fichier.
    $response['file-path-absolute'] = $response['css-path-absolute'] . '/' . $response['file-name'] . '.php';

    return $response;
  }


}
