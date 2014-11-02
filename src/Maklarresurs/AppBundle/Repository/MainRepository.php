<?php
namespace Maklarresurs\AppBundle\Repository;

use Maklarresurs\AppBundle\Entity as Entity;
use Maklarresurs\UserBundle\Entity\User;
use Maklarresurs\UserBundle\Entity\Role;

/**
 * class for data access.
 * maybe we will have to split this up in several repositories later, but for now this is a good starting point
 */
class MainRepository
{

    protected $em;

    public $siteSettings = array();

    /**
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function em()
    {
        return $this->em;
    }

    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getUsersQuery($userType = null)
    {

        $qb = $this->em->createQueryBuilder()
            ->select('users')
            ->from("MaklarresursUserBundle:User", "users")
            ->where("users.userType = :userType")
            ->setParameter("userType", $userType);
        ;
        return $qb->getQuery();
    }

}
