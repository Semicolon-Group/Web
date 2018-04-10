<?php

namespace BaseBundle\Entity;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="address_id", columns={"address_id"})})
 * @ORM\Entity(repositoryClass="BaseBundle\Repository\UserRepository")
 */
class User extends BaseUser implements ParticipantInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=100, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=100, nullable=true)
     */
    private $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gender", type="boolean", nullable=true)
     */
    private $gender;

    /**
     * @var float
     *
     * @ORM\Column(name="height", type="float", precision=10, scale=0, nullable=true)
     */
    private $height;

    /**
     * @var integer
     *
     * @ORM\Column(name="body_type", type="integer", nullable=true)
     */
    private $bodyType;

    /**
     * @var integer
     *
     * @ORM\Column(name="children_number", type="integer", nullable=true)
     */
    private $childrenNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="relegion", type="integer", nullable=true)
     */
    private $relegion;

    /**
     * @var integer
     *
     * @ORM\Column(name="relegion_importance", type="integer", nullable=true)
     */
    private $relegionImportance;

    /**
     * @var boolean
     *
     * @ORM\Column(name="smoker", type="boolean", nullable=true)
     */
    private $smoker;

    /**
     * @var boolean
     *
     * @ORM\Column(name="drinker", type="boolean", nullable=true)
     */
    private $drinker;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_age", type="integer", nullable=true)
     */
    private $minAge;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_age", type="integer", nullable=true)
     */
    private $maxAge;

    /**
     * @var integer
     *
     * @ORM\Column(name="phone", type="integer", nullable=true)
     */
    private $phone;

    /**
     * @var integer
     *
     * @ORM\Column(name="locked", type="smallint", nullable=true)
     */
    private $locked;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=15, nullable=true)
     */
    private $ip;

    /**
     * @var integer
     *
     * @ORM\Column(name="port", type="integer", nullable=true)
     */
    private $port;

    /**
     * @var boolean
     *
     * @ORM\Column(name="role", type="boolean", nullable=true)
     */
    private $role;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", length=65535, nullable=true)
     */
    private $about;

    /**
     * @var integer
     *
     * @ORM\Column(name="civil_status", type="integer", nullable=true)
     */
    private $civilStatus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="connected", type="boolean", nullable=true)
     */
    private $connected;

    /**
     * @var integer
     *
     * @ORM\Column(name="category", type="integer", nullable=true)
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="price_range", type="integer", nullable=true)
     */
    private $priceRange;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="text", length=65535, nullable=true)
     */
    private $link;

    /**
     * @var \Address
     *
     * @ORM\ManyToOne(targetEntity="Address", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     * })
     */
    private $address;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Event", inversedBy="user")
     * @ORM\JoinTable(name="participant",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     *   }
     * )
     */
    private $event;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->event = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTime();
        parent::__construct();
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set gender
     *
     * @param boolean $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return boolean
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set height
     *
     * @param float $height
     *
     * @return User
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set bodyType
     *
     * @param integer $bodyType
     *
     * @return User
     */
    public function setBodyType($bodyType)
    {
        $this->bodyType = $bodyType;

        return $this;
    }

    /**
     * Get bodyType
     *
     * @return integer
     */
    public function getBodyType()
    {
        return $this->bodyType;
    }

    /**
     * Set childrenNumber
     *
     * @param integer $childrenNumber
     *
     * @return User
     */
    public function setChildrenNumber($childrenNumber)
    {
        $this->childrenNumber = $childrenNumber;

        return $this;
    }

    /**
     * Get childrenNumber
     *
     * @return integer
     */
    public function getChildrenNumber()
    {
        return $this->childrenNumber;
    }

    /**
     * Set relegion
     *
     * @param integer $relegion
     *
     * @return User
     */
    public function setRelegion($relegion)
    {
        $this->relegion = $relegion;

        return $this;
    }

    /**
     * Get relegion
     *
     * @return integer
     */
    public function getRelegion()
    {
        return $this->relegion;
    }

    /**
     * Set relegionImportance
     *
     * @param integer $relegionImportance
     *
     * @return User
     */
    public function setRelegionImportance($relegionImportance)
    {
        $this->relegionImportance = $relegionImportance;

        return $this;
    }

    /**
     * Get relegionImportance
     *
     * @return integer
     */
    public function getRelegionImportance()
    {
        return $this->relegionImportance;
    }

    /**
     * Set smoker
     *
     * @param boolean $smoker
     *
     * @return User
     */
    public function setSmoker($smoker)
    {
        $this->smoker = $smoker;

        return $this;
    }

    /**
     * Get smoker
     *
     * @return boolean
     */
    public function getSmoker()
    {
        return $this->smoker;
    }

    /**
     * Set drinker
     *
     * @param boolean $drinker
     *
     * @return User
     */
    public function setDrinker($drinker)
    {
        $this->drinker = $drinker;

        return $this;
    }

    /**
     * Get drinker
     *
     * @return boolean
     */
    public function getDrinker()
    {
        return $this->drinker;
    }

    /**
     * Set minAge
     *
     * @param integer $minAge
     *
     * @return User
     */
    public function setMinAge($minAge)
    {
        $this->minAge = $minAge;

        return $this;
    }

    /**
     * Get minAge
     *
     * @return integer
     */
    public function getMinAge()
    {
        return $this->minAge;
    }

    /**
     * Set maxAge
     *
     * @param integer $maxAge
     *
     * @return User
     */
    public function setMaxAge($maxAge)
    {
        $this->maxAge = $maxAge;

        return $this;
    }

    /**
     * Get maxAge
     *
     * @return integer
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set locked
     *
     * @param integer $locked
     *
     * @return User
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return integer
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return User
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set port
     *
     * @param integer $port
     *
     * @return User
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return integer
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set role
     *
     * @param boolean $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return boolean
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set about
     *
     * @param string $about
     *
     * @return User
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set civilStatus
     *
     * @param integer $civilStatus
     *
     * @return User
     */
    public function setCivilStatus($civilStatus)
    {
        $this->civilStatus = $civilStatus;

        return $this;
    }

    /**
     * Get civilStatus
     *
     * @return integer
     */
    public function getCivilStatus()
    {
        return $this->civilStatus;
    }

    /**
     * Set connected
     *
     * @param boolean $connected
     *
     * @return User
     */
    public function setConnected($connected)
    {
        $this->connected = $connected;

        return $this;
    }

    /**
     * Get connected
     *
     * @return boolean
     */
    public function getConnected()
    {
        return $this->connected;
    }

    /**
     * Set category
     *
     * @param integer $category
     *
     * @return User
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set priceRange
     *
     * @param integer $priceRange
     *
     * @return User
     */
    public function setPriceRange($priceRange)
    {
        $this->priceRange = $priceRange;

        return $this;
    }

    /**
     * Get priceRange
     *
     * @return integer
     */
    public function getPriceRange()
    {
        return $this->priceRange;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return User
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set address
     *
     * @param \BaseBundle\Entity\Address $address
     *
     * @return User
     */
    public function setAddress(\BaseBundle\Entity\Address $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \BaseBundle\Entity\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add event
     *
     * @param \BaseBundle\Entity\Event $event
     *
     * @return User
     */
    public function addEvent(\BaseBundle\Entity\Event $event)
    {
        $this->event[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \BaseBundle\Entity\Event $event
     */
    public function removeEvent(\BaseBundle\Entity\Event $event)
    {
        $this->event->removeElement($event);
    }

    /**
     * Get event
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get age
     *
     * return integer
     */
    public function getAge(){
        $curDate = new \DateTime();
        $age =0 ;
        if($this->birthDate!=null)
            $age = $this->birthDate->diff($curDate)->y;
        return $age;
    }

    /**
     * @var \BaseBundle\Entity\Photo
     */
    private $profilePhoto;

    /**
     * Set profilePhoto
     *
     * @param \BaseBundle\Entity\Photo $photo
     *
     * @return void
     */
    public function setProfilePhoto($photo){
        $this->profilePhoto = $photo;
    }

    /**
     * Get profilePhoto
     *
     * @return \BaseBundle\Entity\Photo
     */
    public function getProfilePhoto(){
        return $this->profilePhoto;
    }

}
