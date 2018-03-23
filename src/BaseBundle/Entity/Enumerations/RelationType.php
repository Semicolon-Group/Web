<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/21/2018
 * Time: 12:21 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class RelationType
{
    const Serious = 0;
    const Friendship = 1;
    const Casual = 2;

    public static function getName($civilStatus){
        switch ($civilStatus){
            case 0:
                return 'Serious';
                break;
            case 1:
                return 'Friendship';
                break;
            case 2:
                return 'Casual';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Serious' => 0, 'Friendship' => 1, 'Casual' => 2);
    }
}