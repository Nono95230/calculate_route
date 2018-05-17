<?php

namespace Drupal\svie_mb;

use Drupal\Core\Entity\EntityInterface;

/**
* Class GenerateCss.
*
* @package Drupal\svie_mb
*/
class GenerateCss {

 /**
  * Génère le fichier css des marques blanches.
  *
  * @param \Drupal\Core\Entity\EntityInterface $entity
  *   Entitée en cours de modification.
  * @param string $action
  *   Action en cours sur l'entitée.
  */
 public static function generateCssFile(EntityInterface $entity, $action = 'save') {
   if ($entity->get('type')->getValue()[0]['target_id'] == "svie_mb") {
     $config = \Drupal::config('svie_mb.settings');
     $path = $config->get('css_path');

     // Génération de l'id.
     $id = strtolower(str_replace(' ', '-', $entity->getTitle()));

     if ($action == 'save') {
       $css = "<?php\n";
       $css .= "header('Content-type: text/css; charset: UTF-8');\n";
       $css .= "header('Cache-control: public');\n";
       $css .= "?>\n\n";

       $css .= "/* Marque blanche " . $entity->getTitle() . " */\n";

       // Récupération des champs du noeud.
       $fields = $entity->getFields();

       foreach ($fields as $fieldMachineName => $field) {
         if (strpos($fieldMachineName, 'field_mb_color') !== FALSE && $field->getFieldDefinition()->get('field_type') == 'color_field_type') {
           // Génération de la classe.

           $name = str_replace('field_mb_color_', '', $fieldMachineName);
           $name = str_replace('_', '-', $name);
           $nbField = count($field->getValue());

           $zone = explode('-', $name)[0];
           $hover = 'hover' == explode('-', $name)[1] ? ' a:hover' : NULL;
           $active = 'active' == explode('-', $name)[1] ? ' .active > a' : NULL;
           $type = end(explode('-', $name));
           $class = $name;

           // Traitement par type.
           switch ($type) {
             case 'fc':
               $typeCss = 'color';
               break;

             case 'bg':
               $typeCss = '  background-color';
               break;

             case 'f':
               $typeCss = '  fill';
               break;

             case 'bt':
               $typeCss = '  border-top-color';
               break;
           }

           // Traitement par nombre d'éléments.
           if (!empty($typeCss)) {
             switch ($nbField) {

               case 1:
                 $color = $typeCss . ": " . $field->getValue()[0]['color'] . " !important;\n";
                 break;

               case 2:
                 $color = "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, ". $field->getValue()[0]['color'] ."), color-stop(100%, " . $field->getValue()[1]['color'] . ")) !important;\n";

                 break;
             }
           }

           // Génération de l'opacité.
           if (!empty($field->getValue()[0]['opacity'])) {
             $opacity = "  opacity: " . $field->getValue()[0]['opacity'] . " !important;\n";
           }

           // Ecriture des propriétés.
           $css .= "#marque-blanche ." . $class . $hover . $active ." {\n" . $color . $opacity . "}\n\n";
         }
       }

       // Génération du fichier.
       file_prepare_directory($path, FILE_CREATE_DIRECTORY | FILE_MODIFY_PERMISSIONS);
       file_put_contents($path . '/'. $id . '.php', $css);
     }
     else {
       unlink($path . '/' . $id . '.php');
     }
   }
 }

}
