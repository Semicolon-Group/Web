<?php

namespace MemberBundle\Controller;

use BaseBundle\Entity\Photo;
use BaseBundle\Form\PhotoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PhotoController extends Controller
{

    /**
     * @Route("/photo/upload", name="upload_photo")
     */
    public function uploadAction(Request $request){
        $photo = new Photo();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $photo->setType(\BaseBundle\Entity\Enumerations\PhotoType::Regular);
            $photo->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/photo/getAll", name="get_photos")
     */
    public function getPhotosAction(Request $request){
        if($request->isXmlHttpRequest()){
            $serializer = new Serializer(array(new ObjectNormalizer()));
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $photos = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('user'=>$user, 'type' => \BaseBundle\Entity\Enumerations\PhotoType::Regular));
            $data = $serializer->normalize($photos);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/photo/delete", name="delete_photo")
     */
    public function deletePhotoAction(Request $request){
        if($request->isXmlHttpRequest()){
            $photo = $this->getDoctrine()->getRepository(Photo::class)->find($request->get('id'));
            $this->getDoctrine()->getManager()->remove($photo);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/photo/asCover", name="make_cover")
     */
    public function makeCoverAction(Request $request){
        if($request->isXmlHttpRequest()){
            $list = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('type'=>\BaseBundle\Entity\Enumerations\PhotoType::Cover));
            if(sizeof($list)!=0){
                $oldCover = $list[0];
                $oldCover->setType(\BaseBundle\Entity\Enumerations\PhotoType::Regular);
                $this->getDoctrine()->getManager()->persist($oldCover);
                $this->getDoctrine()->getManager()->flush();
            }

            $photo = $this->getDoctrine()->getRepository(Photo::class)->find($request->get('id'));
            $photo->setType(\BaseBundle\Entity\Enumerations\PhotoType::Cover);
            $this->getDoctrine()->getManager()->persist($photo);
            $this->getDoctrine()->getManager()->flush();

            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/photo/asProfile", name="make_profile")
     */
    public function makeProfileAction(Request $request){
        if($request->isXmlHttpRequest()){
            $list = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('type'=>\BaseBundle\Entity\Enumerations\PhotoType::Profile));
            if(sizeof($list)!=0){
                $oldProfile = $list[0];
                $oldProfile->setType(\BaseBundle\Entity\Enumerations\PhotoType::Regular);
                $this->getDoctrine()->getManager()->persist($oldProfile);
                $this->getDoctrine()->getManager()->flush();
            }

            $photo = $this->getDoctrine()->getRepository(Photo::class)->find($request->get('id'));
            $photo->setType(\BaseBundle\Entity\Enumerations\PhotoType::Profile);
            $this->getDoctrine()->getManager()->persist($photo);
            $this->getDoctrine()->getManager()->flush();

            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/photo/getCoverPhoto", name="get_cover_photo")
     */
    public function getCoverPhotoAction(Request $request){
        if($request->isXmlHttpRequest()){
            $serializer = new Serializer(array(new ObjectNormalizer()));
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $coverList = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('user'=>$user, 'type' => \BaseBundle\Entity\Enumerations\PhotoType::Cover));
            if(sizeof($coverList)==0){
                return null;
            }else{
                $data = $serializer->normalize($coverList[0]);
                return new JsonResponse($data);
            }
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/photo/getProfilePhoto", name="get_profile_photo")
     */
    public function getProfilePhotoAction(Request $request){
        if($request->isXmlHttpRequest()){
            $serializer = new Serializer(array(new ObjectNormalizer()));
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $profileList = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('user'=>$user, 'type' => \BaseBundle\Entity\Enumerations\PhotoType::Profile));
            if(sizeof($profileList)==0){
                return null;
            }else{
                $data = $serializer->normalize($profileList[0]);
                return new JsonResponse($data);
            }
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
