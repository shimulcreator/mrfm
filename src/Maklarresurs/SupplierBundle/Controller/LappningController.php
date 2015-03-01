<?php

namespace Maklarresurs\SupplierBundle\Controller;

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
 * @Route("/lappning")
 */
class LappningController extends BaseController
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="supplier_lappning")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
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
     * @Route("/pending", name="supplier_lappning_pending")
     * @Method("GET")
     * @Template()
     */
    public function pendingAction()
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
     * @Route("/finished", name="supplier_lappning_finished")
     * @Method("GET")
     * @Template()
     */
    public function finishedAction()
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
     * @Route("/lappnig/show/{id}", name="supplier_lappning_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursAppBundle:Sample')->find($id);

        return array(
            'entity'    => $entity,
        );
    }

    /**
     * Lists all Lappning entities.
     *
     * @Route("/lappning-finish/{id}", name="supplier_lappning_finish")
     * @Method("GET")
     * @Template()
     */
    public function finishAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursAppBundle:Sample')->find($id);

//        $this->getMailer()->sendOrderEmail($id);

        $entity->setIsCompleted(1);
        $em->flush();

        return $this->redirect($this->generateUrl('supplier_lappning_pending'));
    }



}
