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

    public function findByPost($postId, $user){
        return $this->getEntityManager()->createQuery(
            "SELECT r FROM BaseBundle:PostReaction r WHERE r.postId = :postId AND r.user = :user"
        )
            ->setParameter('postId', $postId)
            ->setParameter('user', $user)
            ->execute();
    }

    public function findByPhoto($photoId, $user){
        return $this->getEntityManager()->createQuery(
            "SELECT r FROM BaseBundle:PostReaction r WHERE r.photoId = :photoId AND r.user = :user"
        )
            ->setParameter('photoId', $photoId)
            ->setParameter('user', $user)
            ->execute();
    }
}
