<?php

namespace Maklarresurs\AppBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;

use Maklarresurs\AppBundle\Entity\Lappning;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;
    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    protected $router;
    /**
     * @var \Twig_Environment
     */
    protected $twig;
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    protected $defaultOptions;

    /** @var \Swift_Message */
    private $message;

    public function __construct(
        \Swift_Mailer $mailer,
        UrlGeneratorInterface $router,
        \Twig_Environment $twig,
        ContainerInterface $container
    ) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->container = $container;

        $this->defaultOptions = array(
            'fromEmail' => array('info@maklarresurs.nu' => 'maklarresurs.nu'),
            'mailAssetsDir' => $this->container->getParameter(
                'kernel.root_dir'
            ) . "/../web/uploads",
            'embeds' => array()
        );
    }



    public function sendOrderEmail($id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->container->get('doctrine')->getManager();
        $lappning = $em->getRepository('MaklarresursAppBundle:Lappning')->find($id);

        $template = "MaklarresursUserBundle:email:orderCreated.email.twig";
        $loginUrl = $this->router->generate('home_page');

        $context = compact('lappning', 'loginUrl');

        $this->sendTwigEmail($template, $context, 'khubchena@gmail.com');
    }


    /**
     * @param bool $new
     * @return \Swift_Message
     */
    private function getMessageInstance($new = false)
    {
        if ($new || !$this->message)
                {
                    $this->message = \Swift_Message::newInstance();
                }

        return $this->message;
    }

    /**
     * Returns main repository.
     * @return \Maklarresurs\AppBundle\Repository\MainRepository
     */
    protected function getMainRepo()
    {
        return $this->container->get('maklarresurs_app.main_repo');
    }

    public function sendTwigEmail($templateName, $context, $toEmail, $options = array())
    {
        $options = array_merge($this->defaultOptions, $options);
        $message = $this->getMessageInstance();

        //$context = array_merge(array('locale'=>$this->container->getParameter('locale')), $context);

        $context['embeds'] = array();
        foreach ($options['embeds'] as $name => $path)
            $context['embeds'][$name] = $message->embed(\Swift_Image::fromPath($options['mailAssetsDir'] . $path));


        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $htmlBody = strpos($htmlBody, '##__BLANK__##') !== false ? '' : $htmlBody;

        $message
            ->setSubject($subject)
            ->setFrom($options['fromEmail'])
            ->setTo($toEmail);


        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        //TODO: encapsulate with try catch block to stop transport
        $this->mailer->send($message);
    }
}
