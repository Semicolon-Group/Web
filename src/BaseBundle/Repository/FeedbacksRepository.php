<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 03/04/2018
 * Time: 18:37
 */

namespace BaseBundle\Repository;


class FeedbacksRepository
{
    public function findStateDQL($state)
    {
        $query = $this->getEntityManager()->createQuery("
        select v from BaseBundle:Feedback v where v.state Like :state AND v.date<=CURRENT_DATE () ORDER BY v.date ASC")
            ->setParameter('state', '%' . $state . '%');
        return $query->getResult();
    }

}