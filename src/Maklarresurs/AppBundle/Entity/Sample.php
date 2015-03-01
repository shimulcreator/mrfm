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
class Sample
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
     * @ORM\Column(name="addresses", type="string")
     */
    protected $addresses;

    /**
     * @ORM\Column(name="confirmation", type="boolean")
     */
    protected $confirmation;

    /**
     * @ORM\Column(name="isCompleted", type="boolean")
     */
    protected $isCompleted;

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
     * @var
     * @ORM\OneToMany(targetEntity="Document", mappedBy="lappning")
     */
    protected $documents;

    /**
     * @return mixed
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param mixed $documents
     */
    public function setDocuments($documents)
    {
        $this->documents = $documents;
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

    /**
     * @return mixed
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param mixed $addresses
     */
    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
    }

    /**
     * @return mixed
     */
    public function getIsCompleted()
    {
        return $this->isCompleted;
    }

    /**
     * @param mixed $isCompleted
     */
    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;
    }









}
