<?php

namespace Business\FOSSecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BusinessFOSSecurityBundle:Default:index.html.twig');
    }
}
