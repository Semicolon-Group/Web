<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/19/2018
 * Time: 10:51 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class BodyType
{
    const Rather_not_say = 0;
    const Thin = 1;
    const Overweight = 2;
    const Average = 3;
    const Fit = 4;
    const Herculean = 5;
    const Curvy = 6;

    public static function getName($bodyType){
        switch ($bodyType){
            case 0:
                return 'Rather not say';
                break;
            case 1:
                return 'Thin';
                break;
            case 2:
                return 'Overweight';
                break;
            case 3:
                return 'Average';
                break;
            case 4:
                return 'Fit';
                break;
            case 5:
                return 'Herculean';
                break;
            case 6:
                return 'Curvy';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Rather not say' => 0,
            'Thin' => 1,
            'Overweight' => 2,
            'Average' => 3,
            'Fit' => 4,
            'Herculean' => 5,
            'Curvy' => 6
        );
    }
    
    public static function getNames(){
        $types = [];
        $types [] = self::getName(self::Thin);
        $types [] = self::getName(self::Fit);
        $types [] = self::getName(self::Curvy);
        return $types;
    }
}