<?php

namespace Maklarresurs\AppBundle\Controller;


use \Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Maklarresurs\UserBundle\Entity\User;

class LoginController extends BaseController
{
    /**
     * @return array
     * @Route("/login", name="app_login")
     * @Template()
     */
    public function loginAction()
    {

        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();
        /* @var $session \Symfony\Component\HttpFoundation\Session */

        if ($redirect = $request->get('_redir', null)) {
            $this->getSession()->set('_redir', $redirect);
            $this->setData('_redir', $redirect);
        }
        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
            $this->setNotificationMsg('Username and Password doesnot match!!!', 'error');

        }


        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
        $this->setArrayData(array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token' => $csrfToken,
        ));

        return $this->getAllData();
    }

    /**
     * @Route("/login-failed")
     */
    public function loginFailedAction(){

        $token = $this->get('security.context')->getToken();

        return new \Symfony\Component\HttpFoundation\Response("Login Failed");
    }



}
