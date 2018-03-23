<?php

namespace Member\ExperienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ExperienceController extends Controller
{
    /**
     * @Route("/", name="experiences")
     */
    public function experiencesAction()
    {
        return $this->render('MemberExperienceBundle:Experience:experiences.html.twig', array(
            // ...
        ));
    }

}
