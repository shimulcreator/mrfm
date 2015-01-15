<?php

namespace Maklarresurs\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="area")
 * @ORM\Entity(repositoryClass="Maklarresurs\AppBundle\Entity\AreaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Area {

    const LEVEL_ZONE = 1;
    const LEVEL_DISTRICT = 2;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Region")
     */
    protected $region;

    /**
     * @ORM\ManyToOne(targetEntity="Area")
     */
    protected $parentArea;

    /**
     * @ORM\OneToMany(targetEntity="Area", mappedBy="parentArea")
     */
    protected $childAreas;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Area")
     *  @ORM\JoinTable(name="area_area",
     *      joinColumns={@ORM\JoinColumn(name="area_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")}
     *      )
     */
    protected $parents;

    /**
     * @ORM\Column(type="integer")
     */
    protected $depth;

    /**
     * @ORM\Column(type="string", name="map_ref", nullable=true)
     */
    protected $mapRef;

    function __construct()
    {
        $this->parents = new ArrayCollection();
    }

    public function __toString(){
        return $this->getName();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $childAreas
     */
    public function setChildAreas($childAreas)
    {
        $this->childAreas = $childAreas;
    }

    /**
     * @return mixed
     */
    public function getChildAreas()
    {
        return $this->childAreas;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
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

    /**
     * @param mixed $parentArea
     */
    public function setParentArea($parentArea)
    {
        $this->parentArea = $parentArea;
    }

    /**
     * @return Area
     */
    public function getParentArea()
    {
        return $this->parentArea;
    }

    /**
     * @param mixed $type
     */
    public function setDepth($type)
    {
        $this->depth = $type;
    }

    /**
     * @return mixed
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @param mixed $parents
     */
    public function setParents($parents)
    {
        $this->parents = $parents;
    }

    /**
     * @return mixed
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist()
    {
        $this->parents->clear();
        $area = $this;
        while($parent = $area->getParentArea()) {
            $this->parents->add($parent);
            $area = $parent;
        }

        $this->setDepth($this->getParentArea() ? $this->getParentArea()->getDepth() + 1 : 1);
    }

    /**
     * @param mixed $mapRef
     */
    public function setMapRef($mapRef)
    {
        $this->mapRef = $mapRef;
    }

    /**
     * @return mixed
     */
    public function getMapRef()
    {
        return $this->mapRef;
    }




}