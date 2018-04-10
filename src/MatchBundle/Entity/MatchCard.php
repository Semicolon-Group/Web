<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 29/03/2018
 * Time: 19:26
 */

namespace MatchBundle\Entity;


use BaseBundle\Entity\User;

class MatchCard
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $match;

    /**
     * @var int
     */
    private $enemy;

    /**
     * @var string
     */
    private $photo;

    /**
     * @var int
     */
    private $age;


    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param int $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
    }

    /**
     * @return int
     */
    public function getEnemy()
    {
        return $this->enemy;
    }

    /**
     * @param int $enemy
     */
    public function setEnemy($enemy)
    {
        $this->enemy = $enemy;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }


}