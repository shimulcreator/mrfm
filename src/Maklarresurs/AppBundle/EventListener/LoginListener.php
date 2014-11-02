<?php
namespace Maklarresurs\AppBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine; // for Symfony 2.1.0+
// use Symfony\Bundle\DoctrineBundle\Registry as Doctrine; // for Symfony 2.0.x

use Maklarresurs\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * Custom login listener.
 */
class LoginListener
{
    /**
     * @var Router $router
     */
    protected $router;

    /** @var \Symfony\Component\Security\Core\SecurityContext */
    private $securityContext;

    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    private $redirectPath;

    /**
     * Constructor
     *
     * @param SecurityContext $securityContext
     * @param Doctrine        $doctrine
     */
    public function __construct(Router $router,SecurityContext $securityContext, Doctrine $doctrine)
    {
        $this->router = $router;
        $this->securityContext = $securityContext;
        $this->em              = $doctrine->getManager();
    }

    /**
     * Do the magic.
     *
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $request = $event->getRequest();
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            if ($this->securityContext->isGranted('ROLE_ADMIN')) {
                // do stuff for admins here...
                $request->request->set('_target_path', $this->router->generate('admin_dashboard'));
            }else if ($this->securityContext->isGranted('ROLE_USER')) {
                $request->request->set('_target_path', $this->router->generate('user_dashboard'));
            }


        }


        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // user has logged in using remember_me cookie
        }

        // do some other magic here
        $user = $event->getAuthenticationToken()->getUser();


        // ...
    }
}
