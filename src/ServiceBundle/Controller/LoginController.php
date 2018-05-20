<?php

namespace ServiceBundle\Controller;

use BaseBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;


class LoginController extends Controller
{
    public function Encoded($username,$mdp)
    {
        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
        $user = $user_manager->findUserByUsername($username);
        $encoder = $factory->getEncoder($user);
        $salt = $user->getSalt();
        if($encoder->isPasswordValid($user->getPassword(), $mdp, $salt))
            return true ;
        else
            return false ;
    }
    /**
     * @Route("/Login/{nom}-{mdp}")
     */
    public function LoginAction($nom, $mdp)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findBy(array('firstname' => $nom));
        if (!empty($user)) {
            $username = $user[0]->getUsername();
            if ($this->Encoded($username, $mdp)) {
                $normalizer = new ObjectNormalizer();
                $normalizer->setCircularReferenceLimit(1);
                // Add Circular reference handler
                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });
                $serializer = new Serializer(array($normalizer));
                $data = $serializer->normalize($user);
                return new JsonResponse($data);
            } else {
                return new JsonResponse([]);
            }
        }else
            return new JsonResponse([]);

    }

    /**
     * @Route("/Newpass/{id}-{newpw}")
     */
    public function ChangePassword($id , $newpw)
    {
        $em = $this->getDoctrine()->getManager();
        $user_manager = $this->get('fos_user.user_manager');
        $factory = $this->get('security.encoder_factory');
        $user = $user_manager->findUserByUsername($id);
        $encoder = $factory->getEncoder($user);
        $salt = $user->getSalt();
        var_dump($newpw);

        $hashedPassword = $encoder->encodePassword($newpw, $salt);
        var_dump($hashedPassword);
        $user->setPassword($hashedPassword);
        $user_manager->updatePassword($user);
        $em->flush();
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
