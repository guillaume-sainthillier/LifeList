<?php

namespace LifeList\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Liste
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class Liste {

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
     *      minMessage = "Votre liste doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre liste ne peut pas être plus long que {{ limit }} caractères"
     * )
     * @Assert\NotBlank
     * @Expose
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="LifeList\ApiBundle\Entity\Todo", mappedBy="liste", cascade={"remove", "persist"})
     * @Expose
     */
    private $todos;

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
     * @return Liste
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
     * Constructor
     */
    public function __construct()
    {
        $this->todos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add todos
     *
     * @param \LifeList\ApiBundle\Entity\Todo $todos
     * @return Liste
     */
    public function addTodo(\LifeList\ApiBundle\Entity\Todo $todos)
    {
        $this->todos[] = $todos;

        return $this;
    }

    /**
     * Remove todos
     *
     * @param \LifeList\ApiBundle\Entity\Todo $todos
     */
    public function removeTodo(\LifeList\ApiBundle\Entity\Todo $todos)
    {
        $this->todos->removeElement($todos);
    }

    /**
     * Get todos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTodos()
    {
        return $this->todos;
    }
}
