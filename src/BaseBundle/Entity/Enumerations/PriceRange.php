<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 4/9/2018
 * Time: 7:10 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class PriceRange
{
    const Cheap = 0;
    const Average = 1;
    const Affordable = 2;
    const Expensive = 3;

    public static function getName($priceRange){
        switch ($priceRange){
            case 0:
                return 'Cheap';
                break;
            case 1:
                return 'Average';
                break;
            case 2:
                return 'Affordable';
                break;
            case 3:
                return 'Expensive';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Cheap' => 0,
            'Average' => 1,
            'Affordable' => 2,
            'Expensive' => 3
        );
    }
}