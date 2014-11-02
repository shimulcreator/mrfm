<?php

namespace Maklarresurs\UserBundle\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Maklarresurs\UserBundle\Entity\UserRepository")
 */
class User extends Baseuser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected  $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public  function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     */
    protected $userRoles;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_ame", type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(name="company_name", type="string", nullable=true)
     */
    protected $companyName;

    /**
     * @var string
     * @ORM\Column(name="org_nr", type="string", nullable=true)
     */
    protected $orgNr;

    /**
     * @var string
     * @ORM\Column(name="street_address", type="string", nullable=true)
     */
    protected $streetAddress;

    /**
     * @var string
     * @ORM\Column(name="billing_address", type="string", nullable=true)
     */
    protected $billingAddress;

    /**
     * @var string
     * @ORM\Column(name="contact_person", type="string", nullable=true)
     */
    protected $contactPerson;

    /**
     * @var string
     * @ORM\Column(name="telephone_nr", type="string", nullable=true)
     */
    protected $telephoneNr;

    /**
     * @var string
     * @ORM\Column(name="website", type="string", nullable=true)
     */
    protected $website;

    /**
     * @var
     * @ORM\Column(name="user_type", type="integer", nullable=true)
     */
    protected $userType;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        parent::__construct();
    }

    /**
     * Returns an ARRAY of Role objects with the default Role object appended.
     * @return array
     */
    public function getRoles()
    {
        return array_merge( $this->userRoles->toArray(), array( new Role( parent::ROLE_DEFAULT ) ) );
    }

    /**
     * Returns the true ArrayCollection of UserRoles.
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRolesCollection()
    {
        return $this->userRoles;
    }

    /**
     * Pass a string, get the desired Role object or null.
     * @param string $role
     * @return Role|null
     */
    public function getRole( $role )
    {
        foreach ( $this->getRoles() as $roleItem )
        {
            if ( $role == $roleItem->getRole() )
            {
                return $roleItem;
            }
        }
        return null;
    }

    /**
     * Pass a string, checks if we have that Role. Same functionality as getRole() except returns a real boolean.
     * @param string $role
     * @return boolean
     */
    public function hasRole( $role )
    {
        if ( $this->getRole( $role ) )
        {
            return true;
        }
        return false;
    }

    /**
     * Adds a Role OBJECT to the ArrayCollection. Can't type hint due to interface so throws Exception.
     * @throws Exception
     * @param Role $role
     */
    public function addRole( $role )
    {
        if ( !$role instanceof Role )
        {
            echo  $role;
            throw new \Exception( "addRole takes a Role object as the parameter" );
        }

        if ( !$this->hasRole( $role->getRole() ) )
        {
            $this->userRoles->add( $role );
        }
    }

    /**
     * Pass a string, remove the Role object from collection.
     * @param string $role
     */
    public function removeRole( $role )
    {
        $roleElement = $this->getRole( $role );
        if ( $roleElement )
        {
            $this->userRoles->removeElement( $roleElement );
        }
    }

    /**
     * Pass an ARRAY of Role objects and will clear the collection and re-set it with new UserRoles.
     * Type hinted array due to interface.
     * @param array $userRoles Of Role objects.
     */
    public function setRoles( array $userRoles )
    {
        $this->userRoles->clear();
        foreach ( $userRoles as $role )
        {
            $this->addRole( $role );
        }
    }

    /**
     * Directly set the ArrayCollection of UserRoles. Type hinted as Collection which is the parent of (Array|Persistent)Collection.
     * @param \Doctrine\Common\Collections\Collection $role
     */
    public function setRolesCollection( Collection $collection )
    {
        $this->userRoles = $collection;
    }



    /**
     * @param string $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return string
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param string $contactPerson
     */
    public function setContactPerson($contactPerson)
    {
        $this->contactPerson = $contactPerson;
    }

    /**
     * @return string
     */
    public function getContactPerson()
    {
        return $this->contactPerson;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $orgNr
     */
    public function setOrgNr($orgNr)
    {
        $this->orgNr = $orgNr;
    }

    /**
     * @return string
     */
    public function getOrgNr()
    {
        return $this->orgNr;
    }

    /**
     * @param string $streetAddress
     */
    public function setStreetAddress($streetAddress)
    {
        $this->streetAddress = $streetAddress;
    }

    /**
     * @return string
     */
    public function getStreetAddress()
    {
        return $this->streetAddress;
    }

    /**
     * @param string $telephoneNr
     */
    public function setTelephoneNr($telephoneNr)
    {
        $this->telephoneNr = $telephoneNr;
    }

    /**
     * @return string
     */
    public function getTelephoneNr()
    {
        return $this->telephoneNr;
    }

    /**
     * @param mixed $userRoles
     */
    public function setUserRoles($userRoles)
    {
        $this->userRoles = $userRoles;
    }

    /**
     * @return mixed
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }



}
