<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/21/2018
 * Time: 3:10 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class Topic
{
    const Religion = 0;
    const Relationship = 1;
    const Culture = 2;
    const Sport = 3;
    const General = 4;
    const Mandatory = 5;

    public static function getName($bodyType){
        switch ($bodyType){
            case 0:
                return 'Religion';
                break;
            case 1:
                return 'Relationship';
                break;
            case 2:
                return 'Culture';
                break;
            case 3:
                return 'Sport';
                break;
            case 4:
                return 'General';
                break;
            case 5:
                return 'Mandatory';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Religion' => 0,
            'Relationship' => 1,
            'Culture' => 2,
            'Sport' => 3,
            'General' => 4,
            'Mandatory' => 5
        );
    }
}