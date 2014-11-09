<?php

namespace Maklarresurs\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="region")
 * @ORM\Entity
 */
class Region {

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=2)
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string")
     */
    protected $name;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}