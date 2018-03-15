<?php

namespace AppBundle\Controller;

use BaseBundle\Entity\User;
use FOS\UserBundle\Form\Type\ProfileFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if (($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')))
            return $this->redirectToRoute('news_feed');
        return $this->render('base.html.twig');
    }
}
