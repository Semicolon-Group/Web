<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/21/2018
 * Time: 3:08 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class PostType
{
    const Answer = 0;
    const Picture = 1;
    const Status = 2;
    const Update = 3;

    public static function getName($bodyType){
        switch ($bodyType){
            case 0:
                return 'Answer';
                break;
            case 1:
                return 'Picture';
                break;
            case 2:
                return 'Status';
                break;
            case 3:
                return 'Update';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Answer' => 0,
            'Picture' => 1,
            'Status' => 2,
            'Update' => 3
        );
    }
}