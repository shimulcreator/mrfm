<?php

namespace Maklarresurs\UserBundle\Controller;

use Maklarresurs\AppBundle\Controller\BaseController;
use Maklarresurs\AppBundle\Entity\Area;
use Maklarresurs\AppBundle\Entity\Document;
use Maklarresurs\AppBundle\Entity\Lappning;
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
 * @Route("/order")
 */
class OrderController extends BaseController
{
    /**
     * Lists all Zones entities.
     *
     * @Route("/zones", name="user_zones")
     * @Method("GET")
     * @Template()
     */
    public function zonesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MaklarresursAppBundle:Area')->findAll();

        return array(
            'entities'      => $entities
        );
    }

    /**
     * Lists all Zones entities.
     *
     * @Route("/zone/{id}", name="user_zone_map")
     * @Method("GET")
     * @Template()
     */
    public function zoneMapAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MaklarresursAppBundle:Area')->findAll();

        return array(
            'entities'      => $entities
        );
    }

    /**
     * Lists all addresses entities.
     *
     * @Route("/address", name="user_address")
     * @Method("GET")
     * @Template()
     */
    public function addressAction(Request $request)
    {
        $total = 0;
        $districts = $request->get('district', array());
        $em = $this->getDoctrine()->getManager();
        for($i=0; $i<count($districts); $i++){
            $district = $em->getRepository('MaklarresursAppBundle:Area')->find($districts[$i]);
            $entities[$i] = $em->getRepository('MaklarresursAppBundle:Address')->findByArea($district);
            $total = $total + count($entities[$i]);
        }

        return array(
            'entities'      => $entities,
            'districts' => $districts,
            'total' => $total,
            'loops'  => $i-1
        );
    }

    /**
     * Lists all addresses entities.
     *
     * @Route("/extras", name="user_extras")
     * @Method("GET")
     * @Template()
     */
    public function extrasAction(Request $request)
    {
        $districts = $request->get('district');
        $flyers = $request->get('flyers');

        return array(
            'districts'      => $districts,
            'flyers' => $flyers
        );
    }

    /**
     * Lists all addresses entities.
     *
     * @Route("/create-order", name="user_order_create")
     * @Method("POST")
     * @Template()
     */
    public function createOrderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $zone = $request->get('zone');
        $districts = $request->get('districts');
        $flyers = $request->get('flyers');
        $paperSize = $request->get('paper_size');
        $addressType = $request->get('address_type');

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

        $lappning = new Lappning();
        $lappning->setZone($zone);
        $lappning->setDistricts($districts);
        $lappning->setFlyers($flyers);
        $lappning->setPaperSize($paperSize);
        $lappning->setAddressType($addressType);
        $lappning->setPrice($price);
        $lappning->setUser($user);
        $lappning->setConfirmation(0);

        $em->persist($lappning);
        $em->flush();

        return $this->redirect($this->generateUrl('lappning_uploads', array('id' => $lappning->getId())));
    }


    /**
     * Lists all Business entities.
     *
     * @Route("/create/uploads/{id}", name="lappning_uploads")
     * @Method("GET")
     * @Template()
     */
    public function fileUploadsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursAppBundle:Lappning')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Lappning entity.');
        }
        elseif($entity->getUser() != $this->getUser()){
            throw $this->createNotFoundException('Access Denied');
        }

        $entity = new Document();
        $form   = $this->createForm(new DocumentType(), $entity);

        $images = $em->getRepository('MaklarresursAppBundle:Document')->findByLappning($id);

        return $this->render('@MaklarresursUser/Order/img.html.twig', array(
            'entity' => $entity,
            'lappningId'     => $id,
            'files'    => $images,
            'form'   => $form->createView()
        ));
    }

    /**
     *
     * @return array
     * @Route("/create/file/{id}", options={"expose"=true}, name="lappning_file_uploads_create")
     * @Method("POST")
     * @Template()
     */
    public function createFileAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $lappning = $em->getRepository('MaklarresursAppBundle:Lappning')->find($id);

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

        return $this->redirect($this->generateUrl('user_lappning_order_overview', array('id' => $id)));

    }

    /**
     * Lists all Lappning entities.
     *
     * @Route("/order-overview/{id}", name="user_lappning_order_overview")
     * @Method("GET")
     * @Template()
     */
    public function overviewAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursAppBundle:Lappning')->find($id);

        return array(
            'entity'      => $entity
        );
    }

    /**
     * Lists all Lappning entities.
     *
     * @Route("/order-confirmation/{id}", name="user_lappning_order_confirmation")
     * @Method("POST")
     * @Template()
     */
    public function confirmationAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursAppBundle:Lappning')->find($id);

        $this->getMailer()->sendOrderEmail($id);

//        $entity->setConfirmation(1);
//        $em->flush();

        return $this->redirect($this->generateUrl('user_lappning_order_confirmed'));
    }

    /**
     * Lists all Lappning entities.
     *
     * @Route("/order-confirmed", name="user_lappning_order_confirmed")
     * @Method("GET")
     * @Template()
     */
    public function confirmedAction()
    {
        return array();
    }



    /**
     * @Route("/mapdata.js", name="map_data")
     * @Method("GET")
     */
    public function mapDataAction()
    {
        $districts = $this->getDoctrine()->getRepository('MaklarresursAppBundle:Area')->findBy(array('depth' => Area::LEVEL_DISTRICT));
        $data = array();
        foreach($districts as $district) {
            $data[] = array(
                'name' => $district->getName(),
                'id'   => $district->getId(),
                'parentId' => $district->getParentArea()->getId(),
                'mapRef' => $district->getMapRef(),
                'parentMapRef' => $district->getParentArea()->getMapRef()
            );
        }

        $response = new JsonResponse($data);
        //$response->setSharedMaxAge(500); //TODO: set cache time on production
        return $response;
    }

    /**
     * Deletes an existing Address entity.
     *
     * @Route("/delete", name="order_address_delete")
     * @Method("POST")
     * @Template()
     */
    public function deleteUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->get('id');
        $entity = $em->getRepository('MaklarresursAppBundle:Address')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Address entity.');
        }

        if ($entity) {
            $em->remove($entity);
            $em->flush();

//            return $this->redirect($this->generateUrl('admin_user'));
            $response = 'success';

        }
        else{
            $response = 'failed';
        }

        return new Response($response);
    }
}
