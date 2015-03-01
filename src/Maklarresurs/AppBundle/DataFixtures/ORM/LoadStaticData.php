<?php

namespace Maklarresurs\AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

use Maklarresurs\AppBundle\Entity\Address;
use Symfony\Component\Validator\Constraints\Date;
use Maklarresurs\UserBundle\Entity\Role;
use Maklarresurs\UserBundle\Entity\User;

/**
 * Description of StaticData
 *
 */
class LoadStaticData extends \Doctrine\Common\DataFixtures\AbstractFixture implements \Doctrine\Common\DataFixtures\OrderedFixtureInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $users = array();

    private $addresses = array();

    /** @var User */
    private $admin;

    protected  $user;

    private $ROLE_SUPER_ADMIN;
    private $ROLE_ADMIN;
    private $ROLE_USER;


    public function persist($obj)
    {
        $this->em->persist($obj);
    }

    public function load(\Doctrine\Common\Persistence\ObjectManager $manager)
    {

        $this->em = $manager;
        $this->initRoles();
        $this->loadUsers();
        $this->loadAddress();

        $this->em->flush();
    }


    private function getRandomDate()
    {
        return new \DateTime();
    }


    public function initRoles(){

        $this->ROLE_SUPER_ADMIN = new Role('ROLE_SUPER_ADMIN');
        $this->ROLE_ADMIN = new Role('ROLE_ADMIN');
        $this->ROLE_PS = new Role('ROLE_PS');
        $this->ROLE_USER = new Role('ROLE_USER');

        $em = $this->em;
        $em->persist($this->ROLE_SUPER_ADMIN);
        $em->persist($this->ROLE_ADMIN);
        $em->persist($this->ROLE_PS);
        $em->persist($this->ROLE_USER);

        $em->flush();
    }

    /**
     * preloads user
     */
    public function loadUsers()
    {
        $em = $this->em;

        $user = new User();
        $user->setUsername('admin');
        $user->setPlainPassword('admin');
        $user->setEmail('khubchena@gmail.com');
        $user->setEnabled(true);
        $user->addRole($this->ROLE_SUPER_ADMIN);
        $user->setConfirmationToken(null);
        $user->setUserType(2);

        $user->addRole($this->ROLE_ADMIN);

        $em->persist($user);
        $this->users[] = $user;
        $this->admin = $user;


        $user = new User();

        $user->setFirstName("Shimul");
        $user->setUsername("testuser");
        $user->setPlainPassword("testuser");
        $user->setEmail("testuser@mrfm.com");
        $user->setEnabled(true);
        $user->setSuperAdmin(false);
        $user->setConfirmationToken(null);
        $user->setUserType(1);

        $user->addRole($this->ROLE_USER);

        $em->persist($user);
        $this->addReference("testuser", $user);
        $this->users[] = $user;

        $user = new User();

        $user->setFirstName("Shimul PS");
        $user->setUsername("testps");
        $user->setPlainPassword("testps");
        $user->setEmail("testps@mrfm.com");
        $user->setEnabled(true);
        $user->setSuperAdmin(false);
        $user->setConfirmationToken(null);
        $user->setUserType(3);

        $user->addRole($this->ROLE_PS);

        $em->persist($user);
        $this->addReference("testps", $user);
        $this->users[] = $user;

        $this->em->flush();
    }

    /**
     * preloads address
     */
    public function loadAddress()
    {
        $em = $this->em;

        //adding merchants
        for ($i = 1; $i <= 200; $i++) {
            $area_id = rand(1,27);
            $area = $em->getRepository('MaklarresursAppBundle:Area')->find($area_id);

            $address = new Address();
            $address->setArea($area);
            $address->setName("Test Address {$i}");

            $em->persist($address);
            $this->addReference("address-$i", $address);
            $this->addresses[] = $address;

        }

        $this->em->flush();
    }



    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 3;
    }
}

?>
