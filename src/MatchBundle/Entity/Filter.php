<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 05/04/2018
 * Time: 20:00
 */

namespace MatchBundle\Entity;


class Filter
{
    /**
     * @var int
     */
    private $minAge;

    /**
     * @var int
     */
    private $maxAge;

    /**
     * @var int
     */
    private $minHeight;

    /**
     * @var int
     */
    private $maxHeight;

    /**
     * @var int
     */
    private $distance;

    /**
     * @var int
     */
    private $login;

    /**
     * @var int
     */
    private $smokes;

    /**
     * @var int
     */
    private $drinks;

    /**
     * @var array
     */
    private $body;

    /**
     * @var array
     */
    private $religion;

    /**
     * @var array
     */
    private $status;

    /**
     * @return int
     */
    public function getMinAge()
    {
        return $this->minAge;
    }

    /**
     * @param int $minAge
     * @return Filter
     */
    public function setMinAge($minAge)
    {
        $this->minAge = $minAge;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }

    /**
     * @param int $maxAge
     * @return Filter
     */
    public function setMaxAge($maxAge)
    {
        $this->maxAge = $maxAge;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinHeight()
    {
        return $this->minHeight;
    }

    /**
     * @param int $minHeight
     * @return Filter
     */
    public function setMinHeight($minHeight)
    {
        $this->minHeight = $minHeight;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    /**
     * @param int $maxHeight
     * @return Filter
     */
    public function setMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;
        return $this;
    }

    /**
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param int $distance
     * @return Filter
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
        return $this;
    }

    /**
     * @return int
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param int $login
     * @return Filter
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return int
     */
    public function getSmokes()
    {
        return $this->smokes;
    }

    /**
     * @param int $smokes
     * @return Filter
     */
    public function setSmokes($smokes)
    {
        $this->smokes = $smokes;
        return $this;
    }

    /**
     * @return int
     */
    public function getDrinks()
    {
        return $this->drinks;
    }

    /**
     * @param int $drinks
     * @return Filter
     */
    public function setDrinks($drinks)
    {
        $this->drinks = $drinks;
        return $this;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param array $body
     * @return Filter
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return array
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * @param array $religion
     * @return Filter
     */
    public function setReligion($religion)
    {
        $this->religion = $religion;
        return $this;
    }

    /**
     * @return array
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param array $status
     * @return Filter
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}