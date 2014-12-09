<?php

namespace Maklarresurs\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Lappning
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Lappning
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Maklarresurs\UserBundle\Entity\User")
     */
    protected $user;

    /**
     * @ORM\Column(name="zone", type="string")
     */
    protected $zone;

    /**
     * @ORM\Column(name="districts", type="string")
     */
    protected $districts;

    /**
     * @ORM\Column(name="flyers", type="string")
     */
    protected $flyers;

    /**
     * @ORM\Column(name="paper_size", type="string")
     */
    protected $paperSize;

    /**
     * @ORM\Column(name="address_type", type="string")
     */
    protected $addressType;

    /**
     * @ORM\Column(name="price", type="string")
     */
    protected $price;

    /**
     * @ORM\Column(name="confirmation", type="boolean")
     */
    protected $confirmation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;


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
     * @return mixed
     */
    public function getAddressType()
    {
        return $this->addressType;
    }

    /**
     * @param mixed $addressType
     */
    public function setAddressType($addressType)
    {
        $this->addressType = $addressType;
    }

    /**
     * @return mixed
     */
    public function getDistricts()
    {
        return $this->districts;
    }

    /**
     * @param mixed $districts
     */
    public function setDistricts($districts)
    {
        $this->districts = $districts;
    }

    /**
     * @return mixed
     */
    public function getFlyers()
    {
        return $this->flyers;
    }

    /**
     * @param mixed $flyers
     */
    public function setFlyers($flyers)
    {
        $this->flyers = $flyers;
    }

    /**
     * @return mixed
     */
    public function getPaperSize()
    {
        return $this->paperSize;
    }

    /**
     * @param mixed $paperSize
     */
    public function setPaperSize($paperSize)
    {
        $this->paperSize = $paperSize;
    }

    /**
     * @return mixed
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * @param mixed $zone
     */
    public function setZone($zone)
    {
        $this->zone = $zone;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getConfirmation()
    {
        return $this->confirmation;
    }

    /**
     * @param mixed $confirmation
     */
    public function setConfirmation($confirmation)
    {
        $this->confirmation = $confirmation;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }







}
