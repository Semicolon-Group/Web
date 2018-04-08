<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 4/7/2018
 * Time: 10:22 PM
 */

namespace BaseBundle\Repository;


use BaseBundle\Entity\Enumerations\Topic;
use Doctrine\ORM\EntityRepository;

class AnswerRepository extends EntityRepository
{
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