<?php

namespace PubliciteBundle\Repository;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends \Doctrine\ORM\EntityRepository
{
    public function findSidePubDQL()
    {
        $q=$this->getEntityManager();
        $max = $q->createQuery('Select MAX(m.id) from BaseBundle:Advert m 
        where m.state=:state 
        and m.position=:pos ')->setParameter('state','1')->setParameter('pos' , '2')->getSingleScalarResult();

        return $q->createQuery('select m from BaseBundle:Advert m where  m.id>=:rand 
        ORDER BY m.id ASC ')  ->setParameter('rand',rand(0,$max))
            ->setMaxResults(15)
            ->getResult();

    }
    public function findBigPubDQL()
    {
        $q=$this->getEntityManager();
        $max = $q->createQuery('Select MAX(m.id) FROM BaseBundle:Advert m where   m.position=:pos ')
            ->setParameter('pos','1')->getSingleScalarResult();
        return $q->createQuery('select m from BaseBundle:Advert m where  m.id>=:rand and m.position=:pos
        ORDER BY m.id ASC ')  ->setParameter('rand',rand(0,$max))->setParameter('pos','1')
            ->setMaxResults(1)
            ->getResult();
    }
    public function findThisUserPubs($user)
    {
        $q=$this->getEntityManager()->createQuery('select m from BaseBundle:Advert m where m.business=:a ')
            ->setParameter('a',$user);
        return $q->getResult();

    }
    public function findPubsPourAdminDQL()
    {
        $q=$this->getEntityManager()->createQuery('select m from BaseBundle:Advert m where  m.endDate>=CURRENT_TIMESTAMP() ORDER BY 
                  m.state , m.payed DESC ')
         ;
        return $q->getResult();
    }

    public function UpdateThisAddDQL($id)
    {
        $q=$this->getEntityManager()->createQuery('update BaseBundle:Advert m set m.payed=1 where m.id=:a')
            ->setParameter('a',$id);
        $q->execute();
    }

    public function IncrementClickDQL($id)
    {
        $q=$this->getEntityManager()->createQuery('update BaseBundle:Advert m set m.clicks=m.clicks+1 where m.id=:a')
            ->setParameter('a',$id);
        $q->execute();

    }
    public function findNotifsAdmin()
    {
        $q=$this->getEntityManager()->createQuery('select m from BaseBundle:Advert m where m.state=:a and  m.endDate>=CURRENT_TIMESTAMP() ')
            ->setParameter('a','0');
        return $q->getResult();

    }
    public function findAjaxDQL($user,$id)
    {
        $q=$this->getEntityManager()->createQuery('select m from BaseBundle:Advert m where m.business=:a and m.content =:id ')
            ->setParameter('a',$user)->setParameter('id','%'.$id.'%');
        return $q->getResult();
    }


}
