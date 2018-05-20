<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 4/3/2018
 * Time: 9:50 PM
 */

namespace BaseBundle\Repository;


use BaseBundle\Entity\Advert;
use Doctrine\ORM\EntityRepository;

class AdvertRepository extends EntityRepository
{
    public function getTopFiveAdverts($user){
        $query = $this->getEntityManager()
            ->createQuery("
                select m
                from BaseBundle:Advert m 
                WHERE m.business = :user
                ORDER BY m.clicks DESC 
            ")->setParameter('user', $user)->setMaxResults(5);
        return $query->getResult();
    }

    public function findSidePubDQL()
    {
        $q=$this->getEntityManager();
        $max = $q->createQuery('Select MAX(m.id) from BaseBundle:Advert m 
        where m.position=:pos
        and m.payed=:payed and m.endDate>=CURRENT_TIMESTAMP()')->setParameter('pos' , '2')
            ->setParameter('payed','1')->getSingleScalarResult();

        return $q->createQuery('select m from BaseBundle:Advert m where  m.id>=:rand
        and m.position=:pos and m.payed=:payed ')  ->setParameter('rand',rand(0,$max))->setParameter('pos','2')
            ->setParameter('payed','1')
            ->setMaxResults(10)
            ->getResult();

    }
    public function findBottomPubDQL()
    {
        $q=$this->getEntityManager();
        $max = $q->createQuery('Select MAX(m.id) from BaseBundle:Advert m 
        where  m.position=:pos
        and m.payed=:payed ')->setParameter('pos' , '3')
            ->setParameter('payed','1')->getSingleScalarResult();

        return $q->createQuery('select m from BaseBundle:Advert m where  m.id>=:rand
        and m.position=:pos and m.payed=:payed and m.endDate>=CURRENT_TIMESTAMP() ')  ->setParameter('rand',rand(0,$max))->setParameter('pos','3')
            ->setParameter('payed','1')
            ->setMaxResults(10)
            ->getResult();

    }
    public function findBigPubDQL()
    {
        $q=$this->getEntityManager();
        $max = $q->createQuery('Select MAX(m.id) FROM BaseBundle:Advert m where m.position=:pos ')
            ->setParameter('pos','1')->getSingleScalarResult();
        return $q->createQuery('select m from BaseBundle:Advert m where  m.id>=:rand and m.position=:pos and m.payed=:payed 
          and m.endDate>=CURRENT_TIMESTAMP() ')
            ->setParameter('rand',rand(23,$max))->setParameter('pos','1')->setParameter('payed','1')
            ->setMaxResults(1)
            ->getResult();
    }

    public function findThisUserPubs($user)
    {
        $q=$this->getEntityManager()->createQuery('select m from BaseBundle:Advert m where m.business=:a ORDER BY m.payed ')
            ->setParameter('a',$user);
        return $q->getResult();

    }
    public function findPubsPourAdminDQL()
    {
        $q=$this->getEntityManager()->createQuery(
            'select m from BaseBundle:Advert m where  m.endDate>=CURRENT_TIMESTAMP() ORDER BY 
                  m.state')
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