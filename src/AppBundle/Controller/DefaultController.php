<?php

namespace AppBundle\Controller;

use AppBundle\Event\DefaultEmailEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
//        $this->get('pitech_mailer.resolver.mail')->sendMail(null, 'adumitrache@pitechnologies.ro');

        $this->get('event_dispatcher')->dispatch(DefaultEmailEvent::MAIL_EVENT_NAME, new DefaultEmailEvent());

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
}
