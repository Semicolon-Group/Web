<?php

namespace Business\AdvertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="business_adverts")
     */
    public function indexAction()
    {
        return $this->render('BusinessAdvertBundle:Default:index.html.twig');
    }

    /**
     * @Route("/create", name="business_create_advert")
     */
    public function createAction(){
        return $this->render('BusinessAdvertBundle:Default:create.html.twig');
    }
}
