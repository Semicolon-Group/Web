<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="BaseBundle\Repository\PostRepository")
 */
class Post
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
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=true)
     */
    private $content;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var string
     */
    private $photoUrl;

    /**
     * @var int
     */
    private $type;

    /**
     * @var array
     */
    private $reactions;

    /**
     * @var int
     */
    private $currentReaction;

    /**
     * @var \stdClass
     */
    private $stats;

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
     * @return Post
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
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set user
     *
     * @param \BaseBundle\Entity\User $user
     *
     * @return Post
     */
    public function setUser(\BaseBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set photoUrl
     *
     * @param string $photoUrl
     *
     * @return Post
     */
    public function setPhotoUrl($photoUrl)
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    /**
     * Get photoUrl
     *
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }

    /**
     * Set type
     *
     * @param int $type
     *
     * @return Post
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
 * Get type
 *
 * @return int
 */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Post
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set reactions
     *
     * @param array $reactions
     *
     * @return Post
     */
    public function setReactions($reactions)
    {
        $this->reactions = $reactions;

        return $this;
    }

    /**
     * Get reactions
     *
     * @return array
     */
    public function getReactions()
    {
        return $this->reactions;
    }

    /**
     * Set currentReaction
     *
     * @param int $currentReaction
     *
     * @return Post
     */
    public function setCurrentReaction($currentReaction)
    {
        $this->currentReaction = $currentReaction;

        return $this;
    }

    /**
     * Get currentReaction
     *
     * @return int
     */
    public function getCurrentReaction()
    {
        return $this->currentReaction;
    }

    /**
     * Set stats
     *
     * @param \stdClass $stats
     *
     * @return Post
     */
    public function setStats($stats)
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * Get stats
     *
     * @return \stdClass
     */
    public function getStats()
    {
        return $this->stats;
    }
}
