<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/21/2018
 * Time: 3:04 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class LokedType
{
    const Enabled = 0;
    const Disabled = 1; //l'utilisateur a désactivé son compte par lui meme
    const Banned = 2;

    public static function getName($bodyType){
        switch ($bodyType){
            case 0:
                return 'Enabled';
                break;
            case 1:
                return 'Disabled';
                break;
            case 2:
                return 'Banned';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Enabled' => 0,
            'Disabled' => 1,
            'Banned' => 2
        );
    }
}