<?php

namespace Business\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="business_home")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('business_events');
    }
}
