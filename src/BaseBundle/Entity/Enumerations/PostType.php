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
    const Experience = 0;
    const Picture = 1;
    const Status = 2;

    public static function getName($postType){
        switch ($postType){
            case 0:
                return 'Experience';
                break;
            case 1:
                return 'Picture';
                break;
            case 2:
                return 'Status';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Experience' => 0,
            'Picture' => 1,
            'Status' => 2
        );
    }
}