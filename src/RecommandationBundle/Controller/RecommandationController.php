<?php

namespace RecommandationBundle\Controller;

use BaseBundle\Entity\Address;
use BaseBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RecommandationController extends Controller
{
    /**
     * @Route("/", name="recommandations")
     */
    public function recommandationsAction()
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
            return $this->redirectToRoute('admin_home');
        else if($this->container->get('security.authorization_checker')->isGranted('ROLE_BUSINESS'))
            return $this->redirectToRoute('business_home');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        return $this->render('RecommandationBundle:Recommandation:recommandations.html.twig', array(
            'address' => $user->getAddress()
        ));
    }
}
