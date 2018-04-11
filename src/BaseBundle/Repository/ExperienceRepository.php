<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/30/2018
 * Time: 2:10 PM
 */

namespace BaseBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ExperienceRepository extends EntityRepository
{
    public function getExperiences($user){
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT e 
                FROM BaseBundle:Experience e
                WHERE e.user in (SELECT (l.likeReceiver) FROM BaseBundle:UserLike l WHERE (l.likeSender) = :sender)
            ")->setParameter('sender', $user);
        return $query->getResult();
    }
}