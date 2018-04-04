<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/21/2018
 * Time: 12:08 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class CivilStatus
{
    const Single = 0;
    const Widow = 1;
    const Divorced = 2;

    public static function getName($civilStatus){
        switch ($civilStatus){
            case 0:
                return 'Single';
                break;
            case 1:
                return 'Widow';
                break;
            case 2:
                return 'Divorced';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Single' => 0, 'Widow' => 1, 'Divorced' => 2);
    }
    
    public static function getNames(){
        $types = [];
        $types [] = self::getName(self::Single);
        $types [] = self::getName(self::Divorced);
        $types [] = self::getName(self::Widow);
        return $types;
    }
}