<?php

namespace Maklarresurs\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Dashboard controller.
 *
 * @Route("/")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="user_dashboard")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('MaklarresursUserBundle:Dashboard:index.html.twig');
    }
}
