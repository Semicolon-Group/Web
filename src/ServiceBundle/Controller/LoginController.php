<?php

namespace ServiceBundle\Controller;

use BaseBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class LoginController extends Controller
{
    /**
     * @Route("/Login/{nom}-{mdp}")
     */
    public function LoginAction($nom,$mdp)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findBy( array('firstname' => $nom,
            'lastname' =>$mdp));
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer(array($normalizer));
        $data = $serializer->normalize($user);
        return new JsonResponse($data);
    }

}
