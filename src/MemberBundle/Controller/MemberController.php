<?php

namespace MemberBundle\Controller;

use BaseBundle\Entity\PreferedRelation;
use BaseBundle\Entity\PreferedStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MemberController extends Controller
{
    /**
     * @Route("/profile", name="member_profile")
     */
    public function profileAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $user->setPreferedRelations($this->getDoctrine()->getRepository(PreferedRelation::class)->findBy(array('user' => $user)));
        $user->setPreferedStatuses($this->getDoctrine()->getRepository(PreferedStatus::class)->findBy(array('user' => $user)));
        return $this->render('MemberBundle:Member:profile.html.twig', array(
            'user' => $user
        ));
    }

}
