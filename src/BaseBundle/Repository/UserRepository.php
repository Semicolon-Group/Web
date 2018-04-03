<?php
/**
 * Created by PhpStorm.
 * User: Seif
 * Date: 3/29/2018
 * Time: 11:07 PM
 */

namespace BaseBundle\Repository;


use DateInterval;
use DateTime;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getCountMaleByMonth(){
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');

        $year = (new DateTime)->format("Y");

        $query = $this->getEntityManager()
            ->createQuery("
                select COUNT(m) as total, m.gender, MONTH(m.createdAt) as creation_month, YEAR(m.createdAt) as creation_year
                from BaseBundle:User m 
                WHERE m.roles LIKE :roles AND YEAR(m.createdAt) = :cyear AND m.gender = TRUE
                GROUP BY creation_month, creation_year
            ")->setParameter('roles', 'a:0%')->setParameter('cyear', $year);

        return $query->getResult();
    }

    public function getCountFemaleByMonth(){
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');

        $year = (new DateTime)->format("Y");

        $query = $this->getEntityManager()
            ->createQuery("
                select COUNT(m) as total, m.gender, MONTH(m.createdAt) as creation_month, YEAR(m.createdAt) as creation_year
                from BaseBundle:User m 
                WHERE m.roles LIKE :roles AND YEAR(m.createdAt) = :cyear AND m.gender = FALSE 
                GROUP BY creation_month, creation_year
            ")->setParameter('roles', 'a:0%')->setParameter('cyear', $year);

        return $query->getResult();
    }

    public function getGenderNumber(){
        $query = $this->getEntityManager()
            ->createQuery("
                select COUNT(m) as total, m.gender
                from BaseBundle:User m 
                WHERE m.roles LIKE :roles
                GROUP BY m.gender
            ")->setParameter('roles', 'a:0%');
        return $query->getResult();
    }

    public function getMembersCountByCity(){
        $query = $this->getEntityManager()
            ->createQuery("
                select COUNT(m) as total, a.city as city
                from BaseBundle:User m 
                INNER JOIN m.address a
                WHERE m.roles LIKE :roles
                GROUP BY city
            ")->setParameter('roles', 'a:0%');
        return $query->getResult();
    }


    public function getBusinessCountByMonth(){
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');

        $year = (new DateTime)->format("Y");

        $query = $this->getEntityManager()
            ->createQuery("
                select COUNT(m) as total,  MONTH(m.createdAt) as creation_month, YEAR(m.createdAt) as creation_year
                from BaseBundle:User m 
                WHERE m.roles LIKE :roles AND YEAR(m.createdAt) = :cyear
                GROUP BY creation_month, creation_year
            ")->setParameter('roles', '%"ROLE_BUSINESS"%')->setParameter('cyear', $year);

        return $query->getResult();
    }

    public function getBusinessCountByCategory(){
        $query = $this->getEntityManager()
            ->createQuery("
                select COUNT(m) as total, m.category
                from BaseBundle:User m 
                WHERE m.roles LIKE :roles
                GROUP BY m.category
            ")->setParameter('roles', '%"ROLE_BUSINESS"%');

        return $query->getResult();
    }
}