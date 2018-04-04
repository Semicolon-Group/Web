<?php
/**
 * Created by PhpStorm.
 * User: vaider
 * Date: 31/03/2018
 * Time: 01:14
 */

namespace BaseBundle\Entity;
use Doctrine\ORM\Mapping as ORM;



/**
 * Rating
 *
 * @ORM\Table(name="rating")
 * @ORM\Entity("BaseBundle\Repository\RatingRepository")
 */
class Rating
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    private $rating;

    /**
     * @var int
     *
     * @ORM\Column(name="idE", type="integer", nullable=true)
     */
    private $idE;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return int
     */
    public function getIdE()
    {
        return $this->idE;
    }

    /**
     * @param int $idE
     */
    public function setIdE($idE)
    {
        $this->idE = $idE;
    }

}