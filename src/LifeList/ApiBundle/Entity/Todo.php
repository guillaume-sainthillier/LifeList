<?php

namespace LifeList\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Todo
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ExclusionPolicy("all")
 */
class Todo {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(
     *      min = "2",
     *      max = "50",
     *      minMessage = "Votre tâche doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre tâche ne peut pas être plus long que {{ limit }} caractères"
     * )
     * @Assert\NotBlank
     * @Expose
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="tags", type="array")
     *
     * @Assert\All({
     *     @Assert\NotBlank(message="Le thème ne peut pas être vide")
     * })
     * @Expose
     */
    private $tags;

    /**
     * @var boolean
     *
     * @ORM\Column(name="completed", type="boolean")
     * @Expose
     */
    private $completed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="date")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="date")
     *
     */
    private $dateModification;

    /**
     * @ORM\ManyToOne(targetEntity="LifeList\ApiBundle\Entity\Liste", inversedBy="todos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $liste;

    public function __construct() {
	$this->tags = array();
    }

    /**
     * @ORM\PrePersist()
     */
    public function onCreate() {
	$this->dateCreation = new \DateTime;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function onUpdate() {
	$this->dateModification = new \DateTime;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
	return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Todo
     */
    public function setName($name) {
	$this->name = $name;

	return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
	return $this->name;
    }

    /**
     * Set tags
     *
     * @param array $tags
     * @return Todo
     */
    public function setTags($tags) {
	$this->tags = $tags;

	return $this;
    }

    /**
     * Get tags
     *
     * @return array
     */
    public function getTags() {
	return $this->tags;
    }

    /**
     * Set completed
     *
     * @param boolean $completed
     * @return Todo
     */
    public function setCompleted($completed) {
	$this->completed = $completed;

	return $this;
    }

    /**
     * Get completed
     *
     * @return boolean
     */
    public function getCompleted() {
	return $this->completed;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Todo
     */
    public function setDateCreation($dateCreation) {
	$this->dateCreation = $dateCreation;

	return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation() {
	return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return Todo
     */
    public function setDateModification($dateModification) {
	$this->dateModification = $dateModification;

	return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime
     */
    public function getDateModification() {
	return $this->dateModification;
    }


    /**
     * Set liste
     *
     * @param \LifeList\ApiBundle\Entity\Liste $liste
     * @return Todo
     */
    public function setListe(\LifeList\ApiBundle\Entity\Liste $liste)
    {
        $this->liste = $liste;

        return $this;
    }

    /**
     * Get liste
     *
     * @return \LifeList\ApiBundle\Entity\Liste 
     */
    public function getListe()
    {
        return $this->liste;
    }
}
