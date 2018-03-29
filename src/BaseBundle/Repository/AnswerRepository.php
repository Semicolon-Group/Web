<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 29/03/2018
 * Time: 21:52
 */

namespace BaseBundle\Repository;


use BaseBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class AnswerRepository extends EntityRepository
{
    /**
     * @param $a User
     * @param $b User
     * @return array
     */
    public function getAnswersInCommon($a, $b)
    {
        return $this->getEntityManager()->createQuery(
            "SELECT a FROM BaseBundle:Answer a JOIN BaseBundle:User u
                  WITH a.user = :a
                  WHERE a.question IN (
                      SELECT (ans.question) FROM BaseBundle:Answer ans JOIN BaseBundle:User us
                      WITH ans.user = :b
                  )"
        )
            ->setParameter('a', $a)
            ->setParameter('b', $b)
            ->getResult();
    }
}