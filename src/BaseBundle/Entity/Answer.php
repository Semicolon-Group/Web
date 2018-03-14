<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 *
 * @ORM\Table(name="answer", indexes={@ORM\Index(name="question_id", columns={"question_id"}), @ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="choice_id", columns={"selected_choice_id"}), @ORM\Index(name="fk_answer_choice_id", columns={"selected_choice_id", "question_id"})})
 * @ORM\Entity
 */
class Answer
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
     * @ORM\Column(name="importance", type="integer", nullable=true)
     */
    private $importance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var Choice
     *
     * @ORM\ManyToOne(targetEntity="Choice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="selected_choice_id", referencedColumnName="id")
     * })
     */
    private $selectedChoice;

    /**
     * @var \Question
     *
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * })
     */
    private $question;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Choice", inversedBy="answer")
     * @ORM\JoinTable(name="accepted_choice",
     *   joinColumns={
     *     @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="choice_id", referencedColumnName="id")
     *   }
     * )
     */
    private $choice;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->choice = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set importance
     *
     * @param integer $importance
     *
     * @return Answer
     */
    public function setImportance($importance)
    {
        $this->importance = $importance;

        return $this;
    }

    /**
     * Get importance
     *
     * @return integer
     */
    public function getImportance()
    {
        return $this->importance;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Answer
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
     * Set selectedChoice
     *
     * @param \BaseBundle\Entity\Choice $selectedChoice
     *
     * @return Answer
     */
    public function setSelectedChoice(\BaseBundle\Entity\Choice $selectedChoice = null)
    {
        $this->selectedChoice = $selectedChoice;

        return $this;
    }

    /**
     * Get selectedChoice
     *
     * @return \BaseBundle\Entity\Choice
     */
    public function getSelectedChoice()
    {
        return $this->selectedChoice;
    }

    /**
     * Set question
     *
     * @param \BaseBundle\Entity\Question $question
     *
     * @return Answer
     */
    public function setQuestion(\BaseBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \BaseBundle\Entity\Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set user
     *
     * @param \BaseBundle\Entity\User $user
     *
     * @return Answer
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

    /**
     * Add choice
     *
     * @param \BaseBundle\Entity\Choice $choice
     *
     * @return Answer
     */
    public function addChoice(\BaseBundle\Entity\Choice $choice)
    {
        $this->choice[] = $choice;

        return $this;
    }

    /**
     * Remove choice
     *
     * @param \BaseBundle\Entity\Choice $choice
     */
    public function removeChoice(\BaseBundle\Entity\Choice $choice)
    {
        $this->choice->removeElement($choice);
    }

    /**
     * Get choice
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChoice()
    {
        return $this->choice;
    }
}
