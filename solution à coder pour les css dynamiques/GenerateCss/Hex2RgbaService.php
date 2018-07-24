<?php

namespace Drupal\ausy_mb\Service;

/**
 * Class Hex2Rgba.
 *
 * @package Drupal\ausy_mb
 */
class Hex2RgbaService {

  /**
   * Conversion du code couleur hexadécimal et de l'opacité en rgba
   *
   * @param string $color
   *   contient le code hexadécimal.
   * @param string $opacity
   *   contient la valeur de transparence.
   * @return string
   *   Le code rgba de la couleur.
   */
  public static function convertHex2Rgba($color, $opacity = 1) {

    $color = str_replace('#', '', $color);

    if (strlen($color) == 3) {
      $color = $color{0} . $color{0} .
        $color{1} . $color{1} .
        $color{2} . $color{2};
    }

    list($r, $g, $b) = array(
      $color{0} . $color{1},
      $color{2} . $color{3},
      $color{4} . $color{5}
    );

    $r = hexdec($r);
    $g = hexdec($g);
    $b = hexdec($b);
    $a = $opacity;

    return "rgba($r, $g, $b, $a)";
  }

}
