<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/19/2018
 * Time: 11:03 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class Religion
{
    const Islam = 0;
    const Judaism = 1;
    const Christianity = 2;
    const Atheism = 3;
    const Agnosticism = 4;

    public static function getName($religion){
        switch ($religion){
            case 0:
                return 'Islam';
                break;
            case 1:
                return 'Judaism';
                break;
            case 2:
                return 'Christianity';
                break;
            case 3:
                return 'Atheism';
                break;
            case 4:
                return 'Agnosticism';
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Islam' => 0, 'Judaism' => 1, 'Christianity' => 2, 'Atheism' => 3, 'Agnosticism' => 4);
    }
    
    public static function getNames(){
        $types = [];
        $types [] = self::getName(self::Islam);
        $types [] = self::getName(self::Christianity);
        $types [] = self::getName(self::Judaism);
        $types [] = self::getName(self::Agnosticism);
        $types [] = self::getName(self::Atheism);
        return $types;
    }
}