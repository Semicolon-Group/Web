<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 28/03/2018
 * Time: 23:17
 */

namespace BaseBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function deleteByPost($id){
        return $this->getEntityManager()->createQuery(
            "DELETE FROM BaseBundle:Comment c WHERE c.postId = :id"
        )
            ->setParameter('id', $id)
            ->execute();
    }
}