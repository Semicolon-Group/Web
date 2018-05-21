<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/21/2018
 * Time: 3:06 PM
 */

namespace BaseBundle\Entity\Enumerations;


abstract class NotificationType
{
    const Message = 0;
    const Like = 1;
    const Reaction = 2;
    const Signal = 3;
    const Feedback = 4;
    const Comment = 5;

    public static function getName($bodyType){
        switch ($bodyType){
            case 0:
                return 'Message';
                break;
            case 1:
                return 'Like';
                break;
            case 2:
                return 'Reaction';
                break;
            case 3:
                return 'Signal';
                break;
            case 4:
                return 'Feedback';
                break;
            case 5:
                return 'Comment';
                break;
            default:
                return null;
                break;
        }
    }

    public static function getEnumAsArray(){
        return array('Message' => 0,
            'Like' => 1,
            'Reaction' => 2,
            'Signal' => 3,
            'Feedback' => 4,
            'Comment' => 5
        );
    }
}