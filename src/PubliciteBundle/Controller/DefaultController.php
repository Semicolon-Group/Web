<?php

namespace PubliciteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PubliciteBundle:Default:index.html.twig');
    }
}
