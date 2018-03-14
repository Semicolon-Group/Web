<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostReaction
 *
 * @ORM\Table(name="post_reaction", indexes={@ORM\Index(name="post_reaction", columns={"post_id"}), @ORM\Index(name="fk_user", columns={"user_id"})})
 * @ORM\Entity
 */
class PostReaction
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
     * @var integer
     *
     * @ORM\Column(name="post_id", type="integer", nullable=true)
     */
    private $postId;

    /**
     * @var integer
     *
     * @ORM\Column(name="photo_id", type="integer", nullable=true)
     */
    private $photoId;

    /**
     * @var integer
     *
     * @ORM\Column(name="experience_id", type="integer", nullable=true)
     */
    private $experienceId;

    /**
     * @var integer
     *
     * @ORM\Column(name="reaction", type="integer", nullable=true)
     */
    private $reaction;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set postId
     *
     * @param integer $postId
     *
     * @return PostReaction
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;

        return $this;
    }

    /**
     * Get postId
     *
     * @return integer
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Set photoId
     *
     * @param integer $photoId
     *
     * @return PostReaction
     */
    public function setPhotoId($photoId)
    {
        $this->photoId = $photoId;

        return $this;
    }

    /**
     * Get photoId
     *
     * @return integer
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }

    /**
     * Set experienceId
     *
     * @param integer $experienceId
     *
     * @return PostReaction
     */
    public function setExperienceId($experienceId)
    {
        $this->experienceId = $experienceId;

        return $this;
    }

    /**
     * Get experienceId
     *
     * @return integer
     */
    public function getExperienceId()
    {
        return $this->experienceId;
    }

    /**
     * Set reaction
     *
     * @param integer $reaction
     *
     * @return PostReaction
     */
    public function setReaction($reaction)
    {
        $this->reaction = $reaction;

        return $this;
    }

    /**
     * Get reaction
     *
     * @return integer
     */
    public function getReaction()
    {
        return $this->reaction;
    }

    /**
     * Set user
     *
     * @param \BaseBundle\Entity\User $user
     *
     * @return PostReaction
     */
    public function setUser(\BaseBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BaseBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
