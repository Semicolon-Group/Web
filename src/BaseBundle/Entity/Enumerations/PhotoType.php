<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/21/2018
 * Time: 3:14 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class PhotoType
{
    const Regular = 0;
    const Profile = 1;
    const Cover = 2;

    public static function getName($bodyType){
        switch ($bodyType){
            case 0:
                return 'Regular';
                break;
            case 1:
                return 'Profile';
                break;
            case 2:
                return 'Cover';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Regular' => 0,
            'Profile' => 1,
            'Cover' => 2
        );
    }
}