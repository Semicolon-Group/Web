<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 03/04/2018
 * Time: 18:37
 */

namespace BaseBundle\Repository;


class FeedbacksRepository  extends \Doctrine\ORM\EntityRepository
{
    public function bannedUsers(){
            $qb=$this->getEntityManager()
                ->createQuery("SELECT r 
                            FROM BaseBundle:User r
                            WHERE r.enabled==0
                           ")
            ;
            return $qb->getResult();
        }


}