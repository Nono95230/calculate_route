<?php

namespace Drupal\ausy_mb\Service;

use Drupal\Core\Entity\EntityInterface;
use Drupal\node\Entity\Node;

/**
 * Class GenerateCss.
 *
 * @package Drupal\ausy_mb
 */
class GenerateCssService {


  /**
   * Supprime le fichier css des marques blanches.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Entitée en cours de suppression.
   */
  public static function deleteCssFile(EntityInterface $entity) {

    // Récupération du chemin de fichier.
    $cssPathFile = \Drupal::service('get.css.path.file')
      ->getCssPathFile($entity);

    if (file_exists($cssPathFile['file-path-system'])) {
      unlink($cssPathFile['file-path-system']);
      // Message de succès pour la suppression
      \Drupal::messenger()->addStatus(
        t('Organization\'s graphic configuration successfully deleted.')
      //La configuration graphique de l'organisation a été supprimée avec succès.
      );
    }
  }


  /**
   * Génère le fichier css des marques blanches.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Entitée en cours de modification.
   * @param string $action
   *   Action en cours sur l'entitée.
   */
  public static function generateCssFile(EntityInterface $entity) {

    // Récupération du chemin de fichier.
    $cssPathFile = \Drupal::service('get.css.path.file')
      ->getCssPathFile($entity);

    // Déclaration d'un fichier php de type css
    $css = "<?php\n";
    $css .= "header('Content-type: text/css; charset: UTF-8');\n";
    $css .= "header('Cache-control: public');\n";
    $css .= "?>\n\n";
    $css .= "/* Marque blanche " . $entity->getTitle() . " */\n\n\n";

    // Récupération du champ contenant les propriétés css du contenu Marque Blanche.
    $fieldCssProperties = $entity->get('field_mb_css_properties');

    // Récupération des ids de toutes les propriétés css enregistrés
    $ausyMbCssIds = $fieldCssProperties->getValue();

    // Boucle sur chaque propriété css
    foreach ($ausyMbCssIds as $ausyMbCssId) {
      // Récupèration du noeud contenant un jeu de propriété css
      $nodeAusyMbCss = Node::load($ausyMbCssId['target_id']);

      // Récupèration des données liées au jeu de propriété css
      $selectorType = $nodeAusyMbCss->get('field_mb_css_selector_type')
        ->getString();
      $selectorName = $nodeAusyMbCss->get('title')->getString();
      $propertyName = $nodeAusyMbCss->get('field_mb_css_property_name')
        ->getString();
      $propertyValue = 'inherit';

      // Détermination de la valeur à attribuer
      // selon le nom de la propriété css
      switch ($propertyName) {
        case 'background-color':
        case 'color':
          list($hex, $opacity) = explode(', ', $nodeAusyMbCss->get('field_mb_css_property_color_val')
            ->getString());
          $propertyValue = \Drupal::service('ausy_mb.hex2rgba')
            ->convertHex2Rgba($hex, $opacity);
          break;

        case 'font-family':
          $propertyValue = $nodeAusyMbCss->get('field_mb_css_property_val_ff')
            ->getString();
          $propertyValue = '"' . str_replace('_', ' ', $propertyValue) . '"';
          break;

        case 'background':
        case 'font':
          $propertyValue = $nodeAusyMbCss->get('field_mb_css_property_value')
            ->getString();
          break;
      }

      if ('id' == $selectorType) {
        // Ecriture des propriétés pour un id
        $css .= "#marque-blanche #$selectorName";
        $css .= "{\n  $propertyName : $propertyValue; \n}\n\n";
      }
      else {
        // Ecriture des propriétés pour une ou des class
        $selectorName = str_replace(',', '', $selectorName);
        $classs = explode(' ', $selectorName);

        foreach ($classs as $class) {
          $css .= "#marque-blanche .$class,\n";
        }

        $css = trim($css, ",\n");
        $css .= "{\n  $propertyName : $propertyValue; \n}\n\n";
      }
    }


    // Génération du fichier.
    file_prepare_directory($cssPathFile['css-path-system'], FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS);
    file_put_contents($cssPathFile['file-path-system'], $css);
    chmod($cssPathFile['file-path-system'], 0777);

    // Message de succès pour la génération
    \Drupal::messenger()->addStatus(
      t('Organization\'s graphic configuration successfully saved.')
    //La configuration graphique de l'organisme a été sauvegardée avec succès.
    );

  }


}
