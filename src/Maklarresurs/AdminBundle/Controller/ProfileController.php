<?php

namespace Maklarresurs\AdminBundle\Controller;

use Maklarresurs\AdminBundle\Form\ProfileType;
use Maklarresurs\AdminBundle\Form\UserType;
use Maklarresurs\AppBundle\Controller\BaseController;
use Maklarresurs\AppBundle\Entity\Document;
use Maklarresurs\AppBundle\Form\DocumentType;
use Maklarresurs\UserBundle\Entity\Avatar;
use Maklarresurs\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Profile controller.
 *
 * @Route("/profile")
 */
class ProfileController extends BaseController
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="admin_profile")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MaklarresursUserBundle:User')->find($user->getId());

        return array(
            'entity'    => $entity,
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/edit", name="admin_profile_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $entity = $em->getRepository('MaklarresursUserBundle:User')->find($user->getId());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new ProfileType(), $entity, array(
            'action' => $this->generateUrl('admin_profile_update'),
            'method' => 'PUT',
        ));

//        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/update", name="admin_profile_update")
     * @Method("PUT")
     * @Template("MaklarresursAdminBundle:Profile:edit.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $entity = $em->getRepository('MaklarresursUserBundle:User')->find($user->getId());

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_profile'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * @Route("/change-password", name="admin_change_password")
     * @Template()
     */
    public function changePasswordAction()
    {
        return $this->render('MaklarresursAdminBundle:Profile:changePassword.html.twig');
    }

    /**
     * Changes user password
     *
     * @Route("/update-password", name="admin_update_password")
     */
    public function updatePasswordAction(Request $request){
        $user = $this->getUser();
        $currentPassword = $user->getPassword();

        $oldPassword = $request->get('oldpassword');
        $encoder_service = $this->get('security.encoder_factory');
        $encoder = $encoder_service->getEncoder($user);
        $encoded_pass = $encoder->encodePassword($oldPassword, $user->getSalt());

        if($currentPassword == $encoded_pass){
            $newPassword = $request->get('newpassword');
            $reTypePassword = $request->get('retypepassword');
            if($newPassword == $reTypePassword) {
                $user->setPlainPassword($newPassword);
                $this->getUserManager()->updateUser($user);
                $this->get('session')->getFlashBag()->add('success', 'Password has been updated.');
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Password does not match.');
            }
        } else{
            $this->get('session')->getFlashBag()->add('error', 'Invalid Old Password.');
        }

        return $this->redirect($this->generateUrl('admin_change_password'));
    }


    /**
     * Lists all Campaign entities.
     *
     * @Route("/create/uploads", name="admin_avater_img_uploads")
     * @Method("GET")
     * @Template()
     */
    public function imgUploadsAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $avatars = $em->getRepository('MaklarresursUserBundle:Avatar')->findByUser($user);

        $entity = new Avatar();
        $form   = $this->createForm(new DocumentType(), $entity);

        return $this->render('MaklarresursAdminBundle:Profile:img.html.twig', array(
            'entity' => $entity,
            'avatars'  => $avatars,
            'form'   => $form->createView()
        ));
    }

    /**
     *
     * @return array
     * @Route("/create/img", name="admin_avatar_img_uploads_create")
     * @Method("POST")
     * @Template()
     */
    public function createImgAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser()->getId();

        $user = $em->getRepository('MaklarresursUserBundle:User')->find($user);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $oldEntity = $em->getRepository('MaklarresursUserBundle:Avatar')->findOneByUser($user);
        if($oldEntity){
            $oldImagePath = $oldEntity->getFullImagePath();
            $oldEntity->removeFile($oldImagePath);
            $em->remove($oldEntity);
            $em->flush();
        }
        $entity  = new Avatar();
        $entity->setUser($user);
        $form    = $this->createForm(new DocumentType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            //echo "<pre> File Uploaded!";
            return $this->redirect($this->generateUrl('admin_avater_img_uploads', array('id' => $user->getId())));

        }
    }
}
