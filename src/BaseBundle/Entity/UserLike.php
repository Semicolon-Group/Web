<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserLike
 *
 * @ORM\Table(name="user_like", indexes={@ORM\Index(name="user_like_receiver", columns={"like_receiver_id"}), @ORM\Index(name="fk_like_sender_id", columns={"like_sender_id"})})
 * @ORM\Entity(repositoryClass="BaseBundle\Repository\UserLikeRepository")
 */
class UserLike
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="like_receiver_id", referencedColumnName="id")
     * })
     */
    private $likeReceiver;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="like_sender_id", referencedColumnName="id")
     * })
     */
    private $likeSender;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return UserLike
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set likeReceiver
     *
     * @param \BaseBundle\Entity\User $likeReceiver
     *
     * @return UserLike
     */
    public function setLikeReceiver(\BaseBundle\Entity\User $likeReceiver = null)
    {
        $this->likeReceiver = $likeReceiver;

        return $this;
    }

    /**
     * Get likeReceiver
     *
     * @return \BaseBundle\Entity\User
     */
    public function getLikeReceiver()
    {
        return $this->likeReceiver;
    }

    /**
     * Set likeSender
     *
     * @param \BaseBundle\Entity\User $likeSender
     *
     * @return UserLike
     */
    public function setLikeSender(\BaseBundle\Entity\User $likeSender = null)
    {
        $this->likeSender = $likeSender;

        return $this;
    }

    /**
     * Get likeSender
     *
     * @return \BaseBundle\Entity\User
     */
    public function getLikeSender()
    {
        return $this->likeSender;
    }
}
