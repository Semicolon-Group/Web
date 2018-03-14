<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserBlock
 *
 * @ORM\Table(name="user_block", indexes={@ORM\Index(name="user_block_receiver", columns={"block_receiver_id"}), @ORM\Index(name="fk_user_sender_id", columns={"block_sender_id"})})
 * @ORM\Entity
 */
class UserBlock
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
     *   @ORM\JoinColumn(name="block_receiver_id", referencedColumnName="id")
     * })
     */
    private $blockReceiver;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="block_sender_id", referencedColumnName="id")
     * })
     */
    private $blockSender;



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
     * @return UserBlock
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
     * Set blockReceiver
     *
     * @param \BaseBundle\Entity\User $blockReceiver
     *
     * @return UserBlock
     */
    public function setBlockReceiver(\BaseBundle\Entity\User $blockReceiver = null)
    {
        $this->blockReceiver = $blockReceiver;

        return $this;
    }

    /**
     * Get blockReceiver
     *
     * @return \BaseBundle\Entity\User
     */
    public function getBlockReceiver()
    {
        return $this->blockReceiver;
    }

    /**
     * Set blockSender
     *
     * @param \BaseBundle\Entity\User $blockSender
     *
     * @return UserBlock
     */
    public function setBlockSender(\BaseBundle\Entity\User $blockSender = null)
    {
        $this->blockSender = $blockSender;

        return $this;
    }

    /**
     * Get blockSender
     *
     * @return \BaseBundle\Entity\User
     */
    public function getBlockSender()
    {
        return $this->blockSender;
    }
}
