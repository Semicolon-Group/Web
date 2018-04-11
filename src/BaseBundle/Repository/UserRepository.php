<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 29/03/2018
 * Time: 19:43
 * User: Seif
 * Date: 3/29/2018
 * Time: 11:07 PM
 */

namespace BaseBundle\Repository;


use BaseBundle\Entity\Answer;
use BaseBundle\Entity\User;
use DateInterval;
use DateTime;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param $user User
     * @param $doctrine ManagerRegistry
     * @return array
     */
    public function getUsersNotBlocked($user, $doctrine)
    {
        $result = $this->getEntityManager()->createQuery(
            "SELECT u FROM BaseBundle:User u LEFT JOIN BaseBundle:UserBlock b
                  WITH :user = b.blockReceiver AND u = b.blockSender
                  WHERE b.blockSender IS NULL AND u.id != :id AND u.gender != :gender"
        )
            ->setParameter('user', $user)
            ->setParameter('id', $user->getId())
            ->setParameter('gender', $user->getGender())
            ->getResult();
        foreach ($result as $key => $u){
            /** @var User $u */
            if(in_array('ROLE_ADMIN', $u->getRoles()) || in_array('ROLE_BUSINESS', $u->getRoles())){
                unset($result[$key]);
                continue;
            }
            if($doctrine->getRepository(Answer::class)->getAnswerCount($u) < 10){
                unset($result[$key]);
            }
        }
        return $result;
    }

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
            ")->setParameter('roles', 'a:1:{i:0;s:0:"";}')->setParameter('cyear', $year);

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
            ")->setParameter('roles', 'a:1:{i:0;s:0:"";}')->setParameter('cyear', $year);

        return $query->getResult();
    }

    public function getGenderNumber(){
        $query = $this->getEntityManager()
            ->createQuery("
                select COUNT(m) as total, m.gender
                from BaseBundle:User m 
                WHERE m.roles LIKE :roles
                GROUP BY m.gender
            ")->setParameter('roles', 'a:1:{i:0;s:0:"";}');
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
            ")->setParameter('roles', 'a:1:{i:0;s:0:"";}');
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