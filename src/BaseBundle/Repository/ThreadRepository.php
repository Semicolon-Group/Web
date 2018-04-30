<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 10/04/2018
 * Time: 18:06
 */

namespace BaseBundle\Repository;


use BaseBundle\Entity\User;

class ThreadRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCommonThread($part1, $part2)
    {
        $result = $this->getEntityManager()->createQuery(
            "SELECT t FROM BaseBundle:Thread t JOIN BaseBundle:ThreadMetadata meta WITH t = meta.thread
                  WHERE t.createdBy = :part1 AND meta.participant = :part2
                  OR t.createdBy = :part2 AND meta.participant = :part1"
        )
            ->setParameter('part1', $part1)
            ->setParameter('part2', $part2)
            ->getResult();
        if(empty($result))
            return null;
        return $result[0];
    }

    public function getInboxThreads(User $participant){
        return $this->createQueryBuilder('t')
            ->innerJoin('t.metadata', 'tm')
            ->innerJoin('tm.participant', 'p')

            // the participant is in the thread participants
            ->andWhere('p.id = :user_id')
            ->setParameter('user_id', $participant->getId())

            // the thread does not contain spam or flood
            ->andWhere('t.isSpam = :isSpam')
            ->setParameter('isSpam', false, \PDO::PARAM_BOOL)

            // the thread is not deleted by this participant
            ->andWhere('tm.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

            // there is at least one message written by an other participant
            ->andWhere('tm.lastMessageDate IS NOT NULL')

            // sort by date of last message written by an other participant
            ->orderBy('tm.lastMessageDate', 'DESC')
            ->getQuery()
            ->execute();
    }
}