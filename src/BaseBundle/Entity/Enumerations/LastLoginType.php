<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 04/04/2018
 * Time: 22:38
 */

namespace BaseBundle\Entity\Enumerations;


abstract class LastLoginType
{
    const Day = 0;
    const Week = 1;
    const Month = 2;
    const Year = 3;
    const Anytime = 4;

    public static function getName($lastLogin){
        switch ($lastLogin){
            case 0:
                return 'A day ago';
                break;
            case 1:
                return 'A week ago';
                break;
            case 2:
                return 'A month ago';
                break;
            case 3:
                return 'A year ago';
                break;
            case 4:
                return 'Any time';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Day' => 0, 'Week' => 1, 'Month' => 2, 'Year' => 3, 'Anytime' => 4);
    }

    public static function getNames(){
        $types = [];
        $types [] = self::getName(self::Day);
        $types [] = self::getName(self::Week);
        $types [] = self::getName(self::Month);
        $types [] = self::getName(self::Year);
        return $types;
    }
}