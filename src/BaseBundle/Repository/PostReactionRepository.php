<?php

namespace BaseBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PostReactionRepository extends EntityRepository
{
    public function deleteReactionBy($id){
        return $this->getEntityManager()->createQuery(
            "DELETE FROM BaseBundle:PostReaction r WHERE r.postId = :id OR r.photoId = :id OR r.experienceId = :id"
        )
            ->setParameter('id', $id)
            ->execute();
    }
}
