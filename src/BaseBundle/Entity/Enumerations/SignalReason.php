<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/21/2018
 * Time: 3:00 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class SignalReason
{
    const Inappropriate_Content = 0;
    const Racism = 1;
    const Violence = 2;
    const Harrassment = 3;
    const False_Profile = 4;
    const Other = 5;

    public static function getName($bodyType){
        switch ($bodyType){
            case 0:
                return 'Inappropriate Content';
                break;
            case 1:
                return 'Racism';
                break;
            case 2:
                return 'Violence';
                break;
            case 3:
                return 'Harrassment';
                break;
            case 4:
                return 'False Profile';
                break;
            case 5:
                return 'Other';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Inappropriate Content' => 0,
            'Racism' => 1,
            'Violence' => 2,
            'Harrassment' => 3,
            'False Profile' => 4,
            'Other' => 5
        );
    }
}