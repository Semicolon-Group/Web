<?php

namespace Member\MatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MatchController extends Controller
{
    /**
     * @Route("/", name="matches")
     */
    public function matchesAction()
    {
        return $this->render('MemberMatchBundle:Match:matches.html.twig', array(
            // ...
        ));
    }

}
