<?php

namespace BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Choice
 *
 * @ORM\Table(name="choice", indexes={@ORM\Index(name="question_choice", columns={"question_id"})})
 * @ORM\Entity
 */
class Choice
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
     * @ORM\Column(name="choice", type="string", length=100, nullable=true)
     */
    private $choice;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Answer", inversedBy="choice")
     * @ORM\JoinTable(name="accepted_choice",
     *   joinColumns={
     *     @ORM\JoinColumn(name="choice_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="answer_id", referencedColumnName="id")
     *   }
     * )
     */
    private $answer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answer = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set choice
     *
     * @param string $choice
     *
     * @return Choice
     */
    public function setChoice($choice)
    {
        $this->choice = $choice;

        return $this;
    }

    /**
     * Get choice
     *
     * @return string
     */
    public function getChoice()
    {
        return $this->choice;
    }

    /**
     * Set question
     *
     * @param \BaseBundle\Entity\Question $question
     *
     * @return Choice
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
     * Add answer
     *
     * @param \BaseBundle\Entity\Answer $answer
     *
     * @return Choice
     */
    public function addAnswer(\BaseBundle\Entity\Answer $answer)
    {
        $this->answer[] = $answer;

        return $this;
    }

    /**
     * Remove answer
     *
     * @param \BaseBundle\Entity\Answer $answer
     */
    public function removeAnswer(\BaseBundle\Entity\Answer $answer)
    {
        $this->answer->removeElement($answer);
    }

    /**
     * Get answer
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}
