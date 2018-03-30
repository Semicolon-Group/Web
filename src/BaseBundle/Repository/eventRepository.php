<?php
/**
 * Created by PhpStorm.
 * User: vaider
 * Date: 30/03/2018
 * Time: 00:07
 */
namespace BaseBundle\Repository;
class eventRepository extends \Doctrine\ORM\EntityRepository
{
    public function findEventByName($title)
    {
        $q = $this->getEntityManager()
            ->createQuery("select e from BaseBundle:Event e 
                                    WHERE e.title LIKE :x")
            ->setParameter('x', '%'.$title . '%');
        return $q->getResult();

    }
}