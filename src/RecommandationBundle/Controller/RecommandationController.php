<?php

namespace RecommandationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RecommandationController extends Controller
{
    /**
     * @Route("/", name="recommandations")
     */
    public function recommandationsAction()
    {
        return $this->render('RecommandationBundle:Recommandation:recommandations.html.twig', array(
            // ...
        ));
    }

}
