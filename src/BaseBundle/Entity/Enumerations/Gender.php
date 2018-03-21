<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/19/2018
 * Time: 8:54 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class Gender
{
    const Female = false;
    const Male = true;

    public static function getName($gender){
        if($gender)
            return 'Male';
        return 'Female';
    }

    public static function getEnumAsArray(){
        return array('Female' => false, 'Male' => true);
    }
}