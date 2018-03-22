<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/19/2018
 * Time: 11:00 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class Importance
{
    const Indifferent = 0;
    const Somewhat_Important = 1;
    const Impoortant = 2;

    public static function getName($importance){
        switch ($importance){
            case 0:
                return 'Indifferent';
                break;
            case 1:
                return 'Somewhat important';
                break;
            case 2:
                return 'Impoortant';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Indifferent' => 0, 'Somewhat Important' => 1, 'Important' => 2);
    }
}