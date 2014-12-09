<?php
/**
 * Created by PhpStorm.
 * User: shimul
 * Date: 12/7/14
 * Time: 8:35 PM
 */

namespace Maklarresurs\UserBundle\Controller;

use Maklarresurs\AppBundle\Controller\BaseController;
use Maklarresurs\AppBundle\Entity\BokaVisningsvard;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Mapping\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints\Date;


/**
 * User controller.
 *
 * @Route("/boka-visningsvard")
 */
class BokaVisningsvardController extends BaseController {

    /**
     * Lists all Visningsvard entities.
     *
     * @Route("/order", name="user_boka_visningsvard_order")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MaklarresursAppBundle:BokaVisningsvard')->findAll();

        return array(
            'entities' => $entities
        );
    }

    /**
     * New Order
     *
     * @Route("/order/new", name="user_boka_visningsvard_order_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        return array();
    }

    /**
     * Creating Order
     *
     * @Route("/order/create", name="user_boka_visningsvard_order_create")
     * @Method("POST")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $startTime = $request->get('startTime');
        $endTime = $request->get('endTime');
        $address = $request->get('address');
        $note = $request->get('note');

        $visningsvard = new BokaVisningsvard();
        $visningsvard->setStartDate($startDate);
        $visningsvard->setEndDate($endDate);
        $visningsvard->setStartTime($startTime);
        $visningsvard->setEndTime($endTime);
        $visningsvard->setAddress($address);
        $visningsvard->setNote($note);
        $visningsvard->setUser($user);
        $visningsvard->setConfirm(0);

        $em->persist($visningsvard);
        $em->flush();

        return $this->redirect($this->generateUrl('user_boka_visningsvard_order_overview', array('id' => $visningsvard->getId())));

    }

    /**
     * New Order
     *
     * @Route("/order/overview/{id}", name="user_boka_visningsvard_order_overview")
     * @Method("GET")
     * @Template()
     */
    public function overviewAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MaklarresursAppBundle:BokaVisningsvard')->find($id);

        return array(
            'entity'      => $entity
        );
    }

    /**
     * Lists all Lappning entities.
     *
     * @Route("/order-confirmation/{id}", name="user_boka_visningsvard_order_confirmation")
     * @Method("POST")
     * @Template()
     */
    public function confirmationAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursAppBundle:BokaVisningsvard')->find($id);

        $this->getMailer()->sendOrderEmail($id);

        $entity->setConfirm(1);
        $em->flush();

        return $this->redirect($this->generateUrl('user_boka_visningsvard_order_confirmed'));
    }

    /**
     * Lists all Lappning entities.
     *
     * @Route("/order-confirmed", name="user_boka_visningsvard_order_confirmed")
     * @Method("GET")
     * @Template()
     */
    public function confirmedAction()
    {
        return array();
    }

    /**
     * Edit Order
     *
     * @Route("/order/edit/{id}", name="user_boka_visningsvard_order_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MaklarresursAppBundle:BokaVisningsvard')->find($id);

        return array(
            'entity'      => $entity
        );
    }

    /**
     * Creating Order
     *
     * @Route("/order/update/{id}", name="user_boka_visningsvard_order_update")
     * @Method("POST")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $visningsvard = $em->getRepository('MaklarresursAppBundle:BokaVisningsvard')->find($id);
        $user = $this->getUser();

        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $startTime = $request->get('startTime');
        $endTime = $request->get('endTime');
        $address = $request->get('address');
        $note = $request->get('note');

        $visningsvard->setStartDate($startDate);
        $visningsvard->setEndDate($endDate);
        $visningsvard->setStartTime($startTime);
        $visningsvard->setEndTime($endTime);
        $visningsvard->setAddress($address);
        $visningsvard->setNote($note);
        $visningsvard->setUser($user);
        $visningsvard->setConfirm(0);

        $em->persist($visningsvard);
        $em->flush();

        return $this->redirect($this->generateUrl('user_boka_visningsvard_order_overview', array('id' => $visningsvard->getId())));

    }


} 