<?php

namespace BaseBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PostReactionRepository extends EntityRepository
{
    public function deleteByPost($id){
        return $this->getEntityManager()->createQuery(
            "DELETE FROM BaseBundle:PostReaction r WHERE r.postId = :id"
        )
            ->setParameter('id', $id)
            ->execute();
    }

    public function deleteByPhoto($id){
        return $this->getEntityManager()->createQuery(
            "DELETE FROM BaseBundle:PostReaction r WHERE r.photoId = :id"
        )
            ->setParameter('id', $id)
            ->execute();
    }
}
