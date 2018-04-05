<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 04/04/2018
 * Time: 22:42
 */

namespace BaseBundle\Entity\Enumerations;


class DistanceType
{
    const Close = 0;
    const City = 1;
    const Country = 2;
    const Anywhere = 3;

    public static function getName($distance){
        switch ($distance){
            case 0:
                return 'Close by';
                break;
            case 1:
                return 'Same city';
                break;
            case 2:
                return 'Same country';
                break;
            case 3:
                return 'Anywhere';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Close' => 0, 'City' => 1, 'Country' => 2, 'Anywhere' => 3);
    }

    public static function getNames(){
        $types = [];
        $types [] = self::getName(self::City);
        $types [] = self::getName(self::Country);
        $types [] = self::getName(self::Anywhere);
        return $types;
    }
}