<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 21/03/2018
 * Time: 11:25
 */

namespace BaseBundle\Repository;

use BaseBundle\Entity\Enumerations\PhotoType;

class PhotoRepository extends \Doctrine\ORM\EntityRepository
{
    public function getProfilePhotoUrl($user)
    {
        $result = $this->getEntityManager()->createQuery(
            "SELECT p.image FROM BaseBundle:Photo p WHERE p.user = :user AND p.type = " . PhotoType::Profile
        )
            ->setParameter('user', $user)
            ->getResult();
        if(!empty($result))
            return 'uploads/images/' . $result[0]['image'];
        return 'member_assets/img/member.jpg';
    }

    public function getPostPics($user)
    {
        return $this->getEntityManager()->createQuery(
            "SELECT p FROM BaseBundle:Photo p JOIN BaseBundle:UserLike l WITH p.user = l.likeReceiver
                  WHERE l.likeSender = :user AND p.type in (1,2)"
        )
            ->setParameter('user', $user)
            ->getResult();
    }
}