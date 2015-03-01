<?php

namespace Maklarresurs\UserBundle\Controller;

use Maklarresurs\AppBundle\Controller\BaseController;
use Maklarresurs\AppBundle\Entity\Area;
use Maklarresurs\AppBundle\Entity\Document;
use Maklarresurs\AppBundle\Entity\Lappning;
use Maklarresurs\AppBundle\Entity\Sample;
use Maklarresurs\AppBundle\Form\DocumentType;
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
 * @Route("/lapning-order")
 */
class SampleController extends BaseController
{
    /**
     * Lists all addresses entities.
     *
     * @Route("/create", name="sample_order")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request)
    {
        return array();
    }

    /**
     * Lists all addresses entities.
     *
     * @Route("/create-order", name="sample_order_create")
     * @Method("POST")
     * @Template()
     */
    public function createOrderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $flyers = $request->get('flyers');
        $paperSize = $request->get('paper_size');
        $addressType = $request->get('address_type');
        $addresses = $request->get('addresses');

        $flyersPrice = $flyers*3;

        if($paperSize == "200g"){
            $paperPrice = $flyers * 0.3;
        }
        elseif($paperSize == "250g"){
            $paperPrice = $flyers * 0.6;
        }
        elseif($paperSize == "300g"){
            $paperPrice = $flyers * 0.9;
        }
        elseif($paperSize == "150g"){
            $paperPrice = 0;
        }
        else{
            throw $this->createNotFoundException('Wrong Information');
        }

        if($addressType == "Adresserat"){
            $addressPrice = $flyers * 1;
        }else{
            $addressPrice = 0;
        }

        $price = $flyersPrice + $paperPrice + $addressPrice;

        $lappning = new Sample();
        $lappning->setFlyers($flyers);
        $lappning->setPaperSize($paperSize);
        $lappning->setAddressType($addressType);
        $lappning->setAddresses($addresses);
        $lappning->setPrice($price);
        $lappning->setUser($user);
        $lappning->setConfirmation(0);
        $lappning->setIsCompleted(0);

        $em->persist($lappning);
        $em->flush();


        return $this->redirect($this->generateUrl('sample_uploads', array('id' => $lappning->getId())));
    }


    /**
     * Lists all Business entities.
     *
     * @Route("/create/uploads/{id}", name="sample_uploads")
     * @Method("GET")
     * @Template()
     */
    public function fileUploadsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursAppBundle:Sample')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sample entity.');
        }
        elseif($entity->getUser() != $this->getUser()){
            throw $this->createNotFoundException('Access Denied');
        }

        $entity = new Document();
        $form   = $this->createForm(new DocumentType(), $entity);

        $images = $em->getRepository('MaklarresursAppBundle:Document')->findByLappning($id);

        return $this->render('@MaklarresursUser/Sample/img.html.twig', array(
            'entity' => $entity,
            'lappningId'     => $id,
            'files'    => $images,
            'form'   => $form->createView()
        ));
    }

    /**
     *
     * @return array
     * @Route("/create/file/{id}", options={"expose"=true}, name="sample_file_uploads_create")
     * @Method("POST")
     * @Template()
     */
    public function createFileAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $lappning = $em->getRepository('MaklarresursAppBundle:Sample')->find($id);

        if (!$lappning) {
            throw $this->createNotFoundException('Unable to find Lappning entity.');
        }

        $entity  = new Document();
        $entity->setLappning($lappning);
        $form    = $this->createForm(new DocumentType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

        }

        return $this->redirect($this->generateUrl('user_sample_order_overview', array('id' => $id)));

    }

    /**
     * Lists all Lappning entities.
     *
     * @Route("/order-overview/{id}", name="user_sample_order_overview")
     * @Method("GET")
     * @Template()
     */
    public function overviewAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursAppBundle:Sample')->find($id);

        return array(
            'entity'      => $entity
        );
    }

    /**
     * Lists all Lappning entities.
     *
     * @Route("/order-confirmation/{id}", name="user_sample_order_confirmation")
     * @Method("POST")
     * @Template()
     */
    public function confirmationAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursAppBundle:Sample')->find($id);

        $this->getMailer()->sendOrderEmail($id);

        $entity->setConfirmation(1);
        $em->flush();

        return $this->redirect($this->generateUrl('user_sample_order_confirmed'));
    }

    /**
     * Lists all Lappning entities.
     *
     * @Route("/order-confirmed", name="user_sample_order_confirmed")
     * @Method("GET")
     * @Template()
     */
    public function confirmedAction()
    {
        return array();
    }


    /**
     * Lists all Sample entities.
     *
     * @Route("/order-list", name="user_sample_order_view")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MaklarresursAppBundle:Sample')->findByUser($user);

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all User entities.
     *
     * @Route("/order/show/{id}", name="user_sample_order_show")
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



}
