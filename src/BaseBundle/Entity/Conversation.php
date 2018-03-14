<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conversation
 *
 * @ORM\Table(name="conversation", indexes={@ORM\Index(name="person1_id", columns={"person1_id"}), @ORM\Index(name="person2_id", columns={"person2_id"})})
 * @ORM\Entity
 */
class Conversation
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
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=100, nullable=true)
     */
    private $label;

    /**
     * @var boolean
     *
     * @ORM\Column(name="seen", type="boolean", nullable=true)
     */
    private $seen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="seen_date", type="datetime", nullable=true)
     */
    private $seenDate;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person1_id", referencedColumnName="id")
     * })
     */
    private $person1;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person2_id", referencedColumnName="id")
     * })
     */
    private $person2;



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
     * Set label
     *
     * @param string $label
     *
     * @return Conversation
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set seen
     *
     * @param boolean $seen
     *
     * @return Conversation
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return boolean
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set seenDate
     *
     * @param \DateTime $seenDate
     *
     * @return Conversation
     */
    public function setSeenDate($seenDate)
    {
        $this->seenDate = $seenDate;

        return $this;
    }

    /**
     * Get seenDate
     *
     * @return \DateTime
     */
    public function getSeenDate()
    {
        return $this->seenDate;
    }

    /**
     * Set person1
     *
     * @param \BaseBundle\Entity\User $person1
     *
     * @return Conversation
     */
    public function setPerson1(\BaseBundle\Entity\User $person1 = null)
    {
        $this->person1 = $person1;

        return $this;
    }

    /**
     * Get person1
     *
     * @return \BaseBundle\Entity\User
     */
    public function getPerson1()
    {
        return $this->person1;
    }

    /**
     * Set person2
     *
     * @param \BaseBundle\Entity\User $person2
     *
     * @return Conversation
     */
    public function setPerson2(\BaseBundle\Entity\User $person2 = null)
    {
        $this->person2 = $person2;

        return $this;
    }

    /**
     * Get person2
     *
     * @return \BaseBundle\Entity\User
     */
    public function getPerson2()
    {
        return $this->person2;
    }
}
