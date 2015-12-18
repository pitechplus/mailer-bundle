<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Mailer\Event\MailEvent;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $this
            ->get('event_dispatcher')
            ->dispatch(MailEvent::MAIL, new MailEvent());

        return $this->render(
            'default/index.html.twig',
            [
                'base_dir' => realpath(
                    $this->container->getParameter('kernel.root_dir').'/..'
                ),
            ]
        );
    }
}
