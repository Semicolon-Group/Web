<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 29/03/2018
 * Time: 19:32
 */

namespace MatchBundle\Service;


use BaseBundle\Entity\Answer;
use BaseBundle\Entity\Enumerations\Importance;
use BaseBundle\Entity\Photo;
use BaseBundle\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Mapping\DisconnectedMetadataFactory;
use MatchBundle\Entity\Filter;
use MatchBundle\Entity\MatchCard;
use Doctrine\Common\Persistence\ManagerRegistry;

class MatchCardService
{
    /**
     * @param User $online
     * @param ManagerRegistry $doctrine
     * @param Filter $filter
     * @return array
     */
    public static function getMatches($doctrine, $online, $filter){

        /**
         * @param \stdClass
         * @return int
         */
        function getMatch($couple){
            /**
             * @var Answer $A
             * @var Answer $B
             */
            $A = $couple->online;
            $B = $couple->other;
            if($A->getImportance() == Importance::Indifferent)
                return 1;
            if($A->getChoice()->contains($B->getSelectedChoice()))
                return 1;
            return 0;
        }

        /**
         * @param \stdClass
         * @return float
         */
        function getEnemy($couple){
            /**
             * @var Answer $A
             * @var Answer $B
             */
            $A = $couple->online;
            $B = $couple->other;
            if($A->getImportance() == Importance::Indifferent)
                return 0;

            $imp = 1;
            if($A->getImportance() == Importance::Somewhat_Important)
                $imp = 0.5;
            if(!$A->getChoice()->contains($B->getSelectedChoice()))
                    return $imp;
            return 0;
        }

        /**
         * @param $doctrine ManagerRegistry
         * @param $user User
         * @param $online User
         * @return array
         */
        function getCouples($doctrine, $online, $user){
            $couples = [];
            $onlineAnswers = $doctrine->getRepository(Answer::class)->getAnswersInCommon($online, $user);
            $otherAnswers = $doctrine->getRepository(Answer::class)->getAnswersInCommon($user, $online);
            foreach ($onlineAnswers as $a){
                /** @var  Answer $a */
                /** @var  Answer $b */
                $couple = new \stdClass();
                foreach ($otherAnswers as $b){
                    if($a->getQuestion()->getId() == $b->getQuestion()->getId()){
                        $couple->online = $a;
                        $couple->other = $b;
                        break;
                    }
                }
                $couples[] = $couple;
            }
            return $couples;
        }

        /**
         * @param array $couples
         * @return int
         */
        function getMatchTotal($couples){
            $total = 0;
            $count = 0;
            foreach ($couples as $couple){
                $total += getMatch($couple);
                $count++;
            }
            return $count == 0 ? 0 : ceil(($total / $count) * 100);
        }

        /**
         * @param array $couples
         * @return int
         */
        function getEnemyTotal($couples){
            $total = 0;
            $count = 0;
            foreach ($couples as $couple){
                $total += getEnemy($couple);
                $count++;
            }
            return $count == 0 ? 0 : ceil(($total / $count) * 100);
        }

        /**
         * @param $doctrine ManagerRegistry
         * @param $user User
         * @param $online User
         * @return MatchCard $card
         */
        function getCard($doctrine, $user, $online){
            $card = new MatchCard();
            $card->setUser($user);
            $card->setPhoto($doctrine->getRepository(Photo::class)->getProfilePhotoUrl($user));
            $card->setAge(MatchCardService::getTimeDiff($user->getBirthDate())->year);
            $couples = getCouples($doctrine, $online, $user);
            $card->setMatch(getMatchTotal($couples));
            $card->setEnemy(getEnemyTotal($couples));
            return $card;
        }
        /**
         * @param $doctrine ManagerRegistry
         * @param $users array
         * @param $online User
         * @return array
         */
        function getCards($doctrine, $users, $online){
            $cards = [];
            foreach ($users as $user){
                $cards[] = getCard($doctrine, $user, $online);
            }
            return $cards;
        }

        $users = $doctrine->getRepository(User::class)->getUsersNotBlocked($online);
        $users = FilterService::filter($users, $filter, $online->getAddress());
        $cards = getCards($doctrine, $users, $online);
        return $cards;
    }


    /**
     * @param DateTime $date
     * @return \stdClass
     */
    static function getTimeDiff($date){
        $diff = new \stdClass();
        $diff->year = $date->diff(new DateTime('now'))->y;
        $diff->month = $date->diff(new DateTime('now'))->m;
        $diff->day = $date->diff(new DateTime('now'))->d;
        $diff->week = $diff->day / 7;
        return $diff;
    }
}