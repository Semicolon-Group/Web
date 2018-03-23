<?php

namespace BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserController extends Controller
{
    public function checkEmailUnicityAction(Request $request){
        if($request->isXmlHttpRequest()){
            $serializer = new Serializer(array(new ObjectNormalizer()));
            $rep = $this->getDoctrine()->getRepository('BaseBundle:User');
            $user = $rep->findBy(array('email' => $request->get('email')));
            $data = $serializer->normalize($user == null?true:false);
            return new JsonResponse($data);
        }
        return new JsonResponse(null);
    }

    public function checkUsernameUnicityAction(Request $request){
        if($request->isXmlHttpRequest()){
            $serializer = new Serializer(array(new ObjectNormalizer()));
            $rep = $this->getDoctrine()->getRepository('BaseBundle:User');
            $user = $rep->findBy(array('username' => $request->get('username')));
            $data = $serializer->normalize($user == null?true:false);
            return new JsonResponse($data);
        }
        return new JsonResponse(null);
    }
}
