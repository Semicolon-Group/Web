<?php

namespace ExperienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ExperienceController extends Controller
{
    /**
     * @Route("/", name="experiences")
     */
    public function experiencesAction()
    {
        return $this->render('ExperienceBundle:Experience:experiences.html.twig', array(
            // ...
        ));
    }

}
