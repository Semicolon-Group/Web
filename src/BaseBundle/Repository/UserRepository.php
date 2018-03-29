<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 29/03/2018
 * Time: 19:43
 */

namespace BaseBundle\Repository;


use BaseBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param $user User
     * @return array
     */
    public function getUsersNotBlocked($user)
    {
        return $this->getEntityManager()->createQuery(
            "SELECT u FROM BaseBundle:User u LEFT JOIN BaseBundle:UserBlock b
                  WITH :user = b.blockReceiver AND u = b.blockSender
                  WHERE b.blockSender IS NULL AND u.id != :id"
        )
            ->setParameter('user', $user)
            ->setParameter('id', $user->getId())
            ->getResult();
    }
}