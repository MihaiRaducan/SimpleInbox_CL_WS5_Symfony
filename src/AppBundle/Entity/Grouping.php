<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Grouping
 *
 * @ORM\Table(name="grouping")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GroupingRepository")
 */
class Grouping
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Person", mappedBy="groupings")
     */
    private $persons;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Grouping
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Grouping
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getPersons()
    {
        return $this->persons;
    }

    /**
     * @param mixed $persons
     */
    public function setPersons($persons)
    {
        $this->persons = $persons;
    }

    /**
     * @param Person $person
     */
    public function addPerson(Person $person)
    {
        if ($this->persons->contains($person)) {
            return;
        }
        $this->persons->add($person);
        $person->addToGrouping($this);
    }

    /**
     * @param Person $person
     */
    public function removePerson(Person $person)
    {
        if (!$this->persons->contains($person)) {
            return;
        }
        $this->persons->removeElement($person);
        $person->removeFromGrouping($this);
    }

    public function __toString()
    {
       return $this->getName();
    }
}
