<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 4/3/2018
 * Time: 9:50 PM
 */

namespace BaseBundle\Repository;


use Doctrine\ORM\EntityRepository;

class AdvertRepository extends EntityRepository
{
    public function getTopFiveAdverts($user){
        $query = $this->getEntityManager()
            ->createQuery("
                select m
                from BaseBundle:Advert m 
                WHERE m.business = :user
                ORDER BY m.clicks
            ")->setParameter('user', $user)->setMaxResults(5);
        return $query->getResult();
    }
}