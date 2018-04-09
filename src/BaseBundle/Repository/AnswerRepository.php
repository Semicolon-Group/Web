<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 29/03/2018
 * Time: 21:52
 */

namespace BaseBundle\Repository;


use BaseBundle\Entity\User;
use BaseBundle\Entity\Enumerations\Topic;
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
    public function getMandatoryAnswers($user){
        $query = $this->getEntityManager()
            ->createQuery("
                SELECT a 
                FROM BaseBundle:Answer a INNER JOIN a.question q
                WHERE a.user = :user AND q.topic = :topic")
            ->setParameter('user', $user)->setParameter('topic', Topic::Mandatory);
        return $query->getResult();
    }
}