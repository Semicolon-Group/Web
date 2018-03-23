<?php

namespace Business\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="business_events")
     */
    public function indexAction()
    {
        return $this->render('BusinessEventBundle:Default:index.html.twig');
    }

    /**
     * @Route("/create", name="business_create_event")
     */
    public function createAction(){
        return $this->render('BusinessEventBundle:Default:create.html.twig');
    }
}
