<?php

namespace ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{

    /**
     * show answer.
     *
     * @Route("/allanswer", name="answer_getall")
     */
    public function AllAnswerAction()
    {
        $answers = $this->getDoctrine()->getManager()
            ->getRepository('BaseBundle::Answer')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($answers);
        return new JsonResponse($formatted);
    }
}
