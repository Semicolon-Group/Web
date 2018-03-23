<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 21/03/2018
 * Time: 11:25
 */

namespace BaseBundle\Repository;


class PhotoRepository extends \Doctrine\ORM\EntityRepository
{
    public function getProfilePhotoUrl($user)
    {
        return $this->getEntityManager()->createQuery(
            "SELECT p.url FROM BaseBundle:Photo p WHERE p.user = :user AND p.type = 1"
        )
            ->setParameter('user', $user)
            ->getResult()[0]['url'];
    }

    public function getPostPics($user)
    {
        return $this->getEntityManager()->createQuery(
            "SELECT p FROM BaseBundle:Photo p JOIN BaseBundle:UserLike l WITH p.user = l.likeReceiver
                  WHERE l.likeSender = :user AND p.type in (1,2)"
        )
            ->setParameter('user', $user)
            ->getResult();
    }
}