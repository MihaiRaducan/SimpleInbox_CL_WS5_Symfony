<?php


namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Person", mappedBy="user")
     */
    private $persons;

    /**
     * @ORM\OneToMany(targetEntity="Grouping", mappedBy="user")
     */
    private $groupings;

    public function __construct()
    {
        parent::__construct();
        $this->persons = new ArrayCollection();
        $this->groupings = new ArrayCollection();
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
     * @return mixed
     */
    public function getGroupings()
    {
        return $this->groupings;
    }

    /**
     * @param mixed $groupings
     */
    public function setGroupings($groupings)
    {
        $this->groupings = $groupings;
    }
}
