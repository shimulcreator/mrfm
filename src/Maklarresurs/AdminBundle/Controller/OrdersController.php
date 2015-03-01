<?php

namespace Maklarresurs\AdminBundle\Controller;

use Maklarresurs\AppBundle\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Mapping\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Maklarresurs\AppBundle\Repository\MainRepository;

/**
 * Orders controller.
 *
 * @Route("/orders")
 */
class OrdersController extends BaseController
{
    /**
     * Lists all User entities.
     *
     * @Route("/lappning", name="admin_orders_lappning")
     * @Method("GET")
     * @Template()
     */
    public function lappningAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MaklarresursAppBundle:Sample')->findAll();

        return array(
            'entities'    => $entities,
        );
    }

    /**
     * Lists all User entities.
     *
     * @Route("/lappnig/show/{id}", name="admin_orders_lappning_show")
     * @Method("GET")
     * @Template()
     */
    public function showLappningAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursAppBundle:Sample')->find($id);

        return array(
            'entity'    => $entity,
        );
    }

    /**
     * Lists all User entities.
     *
     * @Route("/boka", name="admin_orders_boka")
     * @Method("GET")
     * @Template()
     */
    public function bokaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MaklarresursAppBundle:BokaVisningsvard')->findAll();

        return array(
            'entities'    => $entities,
        );
    }

    /**
     * Lists all User entities.
     *
     * @Route("/boka/show/{id}", name="admin_orders_boka_show")
     * @Method("GET")
     * @Template()
     */
    public function showBokaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursAppBundle:BokaVisningsvard')->find($id);

        return array(
            'entity'    => $entity,
        );
    }


}
