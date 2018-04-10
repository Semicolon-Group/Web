<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 4/9/2018
 * Time: 7:02 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class Categorie
{
    const Pastryshop = 0;
    const Restaurant = 1;
    const Goods = 2;
    const Makeup = 3;
    const Entertainment = 4;
    const Cafe = 5;
    const Hotel = 6;

    public static function getName($bodyType){
        switch ($bodyType){
            case 0:
                return 'Pastryshop';
                break;
            case 1:
                return 'Restaurant';
                break;
            case 2:
                return 'Goods';
                break;
            case 3:
                return 'Makeup';
                break;
            case 4:
                return 'Entertainment';
                break;
            case 5:
                return 'Cafe';
                break;
            case 6:
                return 'Hotel';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Pastryshop' => 0,
            'Restaurant' => 1,
            'Goods' => 2,
            'Makeup' => 3,
            'Entertainment' => 4,
            'Cafe' => 5,
            'Hotel' => 6
        );
    }
}