<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 10/04/2018
 * Time: 18:06
 */

namespace BaseBundle\Repository;


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
}