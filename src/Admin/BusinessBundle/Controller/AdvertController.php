<?php

namespace Admin\BusinessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdvertController extends Controller
{
    /**
     * @Route("/Modifier")
     */
    public function ModifierAction()
    {
        return $this->render('AdminBusinessBundle:Advert:modifier.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/Lister")
     */
    public function ListerAction()
    {
        return $this->render('AdminBusinessBundle:Advert:lister.html.twig', array(
            // ...
        ));
    }

}
