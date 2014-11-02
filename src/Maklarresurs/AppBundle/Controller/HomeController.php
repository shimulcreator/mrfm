<?php

namespace Maklarresurs\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Home controller.
 *
 * @Route("/")
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="home_page")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('MaklarresursAppBundle:Home:index.html.twig');
    }
}
