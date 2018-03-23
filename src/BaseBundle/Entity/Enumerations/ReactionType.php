<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/21/2018
 * Time: 2:57 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class ReactionType
{
    const Like = 0;
    const Laugh = 1;
    const Smile = 2;
    const Love = 3;
    const Scowl = 4;

    public static function getName($bodyType){
        switch ($bodyType){
            case 0:
                return 'Like';
                break;
            case 1:
                return 'Laugh';
                break;
            case 2:
                return 'Smile';
                break;
            case 3:
                return 'Love';
                break;
            case 4:
                return 'Scowl';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Like' => 0,
            'Laugh' => 1,
            'Smile' => 2,
            'Love' => 3,
            'Scowl' => 4
        );
    }
}