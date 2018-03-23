<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 21/03/2018
 * Time: 15:43
 */

namespace BaseBundle\Repository;


class PostRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPosts($user)
    {
        return $this->getEntityManager()->createQuery(
            "SELECT p FROM BaseBundle:Post p JOIN BaseBundle:UserLike l WITH p.user = l.likeReceiver
                  WHERE l.likeSender = :user"
        )
            ->setParameter('user', $user)
            ->getResult();
    }
}