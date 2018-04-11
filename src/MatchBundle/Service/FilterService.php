<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 05/04/2018
 * Time: 20:12
 */

namespace MatchBundle\Service;


use BaseBundle\Entity\Address;
use BaseBundle\Entity\Enumerations\BodyType;
use BaseBundle\Entity\Enumerations\CivilStatus;
use BaseBundle\Entity\Enumerations\DistanceType;
use BaseBundle\Entity\Enumerations\LastLoginType;
use BaseBundle\Entity\Enumerations\Religion;
use MatchBundle\Entity\Filter;

class FilterService
{
    /**
     * @param array
     * @param Filter $filter
     * @param Address $address
     * @return array
     */
    static function filter($users, $filter, $address){
        if($filter == null)
            return $users;

        foreach ($users as $key => $user){
            /** @var \BaseBundle\Entity\User $user */
            if(MatchCardService::getTimeDiff($user->getBirthDate())->year < $filter->getMinAge() ||
            MatchCardService::getTimeDiff($user->getBirthDate())->year > $filter->getMaxAge()){
                unset($users[$key]);
                continue;
            }
            if($user->getHeight() * 100 < $filter->getMinHeight() || $user->getHeight() * 100 > $filter->getMaxHeight()){
                unset($users[$key]);
                continue;
            }
            if($filter->getDrinks() != -1 && $user->getDrinker() != $filter->getDrinks()){
                unset($users[$key]);
                continue;
            }
            if($filter->getSmokes() != -1 && $user->getSmoker() != $filter->getSmokes()){
                unset($users[$key]);
                continue;
            }
            if($filter->getDistance() == DistanceType::getName(DistanceType::City) &&
                $address->getCity() != $user->getAddress()->getCity()){
                unset($users[$key]);
                continue;
            }
            if($filter->getDistance() == DistanceType::getName(DistanceType::Country) &&
                $address->getCountry() != $user->getAddress()->getCountry()){
                unset($users[$key]);
                continue;
            }
            if(!empty($filter->getBody()) && !in_array(BodyType::getName($user->getBodyType()), $filter->getBody())){
                unset($users[$key]);
                continue;
            }
            if(!empty($filter->getReligion()) && !in_array(Religion::getName($user->getRelegion()), $filter->getReligion())){
                unset($users[$key]);
                continue;
            }
            if(!empty($filter->getStatus()) && !in_array(CivilStatus::getName($user->getCivilStatus()), $filter->getStatus())){
                unset($users[$key]);
                continue;
            }
            if($filter->getLogin() == LastLoginType::getName(LastLoginType::Year) &&
                MatchCardService::getTimeDiff($user->getLastLogin())->year > 1){
                unset($users[$key]);
                continue;
            }
            if($filter->getLogin() == LastLoginType::getName(LastLoginType::Month) &&
                MatchCardService::getTimeDiff($user->getLastLogin())->month > 1){
                unset($users[$key]);
                continue;
            }
            if($filter->getLogin() == LastLoginType::getName(LastLoginType::Week) &&
                MatchCardService::getTimeDiff($user->getLastLogin())->week > 1){
                unset($users[$key]);
                continue;
            }
            if($filter->getLogin() == LastLoginType::getName(LastLoginType::Day) &&
                MatchCardService::getTimeDiff($user->getLastLogin())->day > 1){
                unset($users[$key]);
                continue;
            }
        }
        return $users;
    }
}