<?php

namespace ServiceBundle\Controller;

use BaseBundle\Entity\Photo;
use BaseBundle\Entity\User;
use BaseBundle\Entity\UserBlock;
use BaseBundle\Entity\UserLike;
use BaseBundle\Form\PhotoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SeifController extends Controller
{

    /**
     * @Route("/seif/getUser/{id}", name="seif_getUser")
     */
    public function getUserAction(Request $request, $id){
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if($user!=null){
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
        return new Response(Response::HTTP_NOT_FOUND);
    }
    /**
     * @Route("/badis/getUser2", name="badis_getUser")
     */
    public function getUserActionBadis(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findByEmail(['email', ($request->get('email'))]);
        if (!empty($user)) {
            $a = $user[0];
            if ($a != null) {
                $data = [];
                $c = [
                    'id' => $a->getId()
                ];
                $data[] = $c;
                $serializer = new Serializer([new ObjectNormalizer()]);
                $data = $serializer->normalize($data);
                return new JsonResponse($data);
            }
            return new Response(Response::HTTP_NOT_FOUND);
        }
    }
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/seif/test", name="seif_test")
     */
    public function testEditAction(Request $request){
        return new JsonResponse((boolean)$request->get('test'));
    }

    /**
     * @Route("/seif/editUser/{id}", name="seif_editUser")
     */
    public function editUserAction(Request $request, $id){
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if($user!=null){
            $user->setEmail($request->get('email'));
            $user->setFirstname($request->get('firstname'));
            $user->setLastname($request->get('lastname'));
            $user->setUsername($request->get('username'));
            $user->setGender($request->get('gender'));
            $user->setBirthDate(new \DateTime($request->get('birthday')));
            $user->setMinAge($request->get('min_age'));
            $user->setMaxAge($request->get('max_age'));
            $user->setHeight($request->get('height'));
            $user->setBodyType($request->get('body_type'));
            $user->setRelegion($request->get('religion'));
            $user->setRelegionImportance($request->get('religion_importance'));
            $user->setChildrenNumber($request->get('chilren_number'));
            $user->setSmoker($request->get('smoker'));
            $user->setDrinker($request->get('drinker'));
            $user->setPhone($request->get('phone'));
            $user->setCivilStatus($request->get('civil_status'));
            $user->getAddress()->setCity($request->get('city'));
            $user->getAddress()->setCountry($request->get('country'));
            $user->getAddress()->setLongitude($request->get("lng"));
            $user->getAddress()->setLatitude($request->get("lat"));

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

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
        return new Response(Response::HTTP_NOT_FOUND);
    }
    /**
     * @Route("/badis/editUser/{id}", name="badis_editUser")
     */
    public function editUserActionBadis(Request $request, $id){
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if($user!=null){
            $user->setEmail($request->get('email'));
            $user->setFirstname($request->get('firstname'));
            $user->setLastname($request->get('lastname'));
            $user->setUsername($request->get('username'));
            $user->setGender($request->get('gender'));
            $user->setBirthDate(new \DateTime($request->get('birthday')));
            $user->setMinAge($request->get('min_age'));
            $user->setMaxAge($request->get('max_age'));
            $user->setHeight($request->get('height'));
            $user->setBodyType($request->get('body_type'));
            $user->setRelegion($request->get('religion'));
            $user->setRelegionImportance($request->get('religion_importance'));
            $user->setChildrenNumber($request->get('chilren_number'));
            $user->setSmoker($request->get('smoker'));
            $user->setDrinker($request->get('drinker'));
            $user->setPhone($request->get('phone'));
            $user->setCivilStatus($request->get('civil_status'));
            $user->getAddress()->setCity($request->get('city'));
            $user->getAddress()->setCountry($request->get('country'));
            $user->getAddress()->setLongitude($request->get("lng"));
            $user->getAddress()->setLatitude($request->get("lat"));
            $user->setPassword($request->get('password'));

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

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
        return new Response(Response::HTTP_NOT_FOUND);
    }


    /**
     * @param Request $request
     * @param $idSender
     * @param $idReceiver
     * @Route("/seif/likeUser/{idSender}/{idReceiver}", name="seif_likeUser")
     */
    public function likeUserAction(Request $request, $idSender, $idReceiver){
        $userRep = $this->getDoctrine()->getRepository(User::class);
        $sender = $userRep->find($idSender);
        $receiver = $userRep->find($idReceiver);
        if($sender != null && $receiver != null){
            $like = new UserLike();
            $like->setLikeSender($sender);
            $like->setLikeReceiver($receiver);
            $like->setDate(new \DateTime());
            $this->getDoctrine()->getManager()->persist($like);
            $this->getDoctrine()->getManager()->flush();

            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $idSender
     * @param $idReceiver
     * @Route("/seif/dislikeUser/{idSender}/{idReceiver}", name="seif_dislikeUser")
     */
    public function dislikeUserAction(Request $request, $idSender, $idReceiver){
        $userRep = $this->getDoctrine()->getRepository(User::class);
        $sender = $userRep->find($idSender);
        $receiver = $userRep->find($idReceiver);
        if($sender != null && $receiver != null){
            $like = $this->getDoctrine()->getRepository(UserLike::class)->findBy(array('likeSender' => $sender, 'likeReceiver' => $receiver))[0];
            if($like != null){
                $this->getDoctrine()->getManager()->remove($like);
                $this->getDoctrine()->getManager()->flush();
                return new Response(Response::HTTP_OK);
            }
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $idSender
     * @param $idReceiver
     * @Route("/seif/blockUser/{idSender}/{idReceiver}", name="seif_blockUser")
     */
    public function blockUserAction(Request $request, $idSender, $idReceiver){
        $userRep = $this->getDoctrine()->getRepository(User::class);
        $sender = $userRep->find($idSender);
        $receiver = $userRep->find($idReceiver);
        if($sender != null && $receiver != null){
            $likeList = $this->getDoctrine()->getRepository(UserLike::class)->findBy(array('likeReceiver' => $receiver, 'likeSender' => $sender));
            if(sizeof($likeList) != 0){
                $this->getDoctrine()->getManager()->remove($likeList[0]);
                $this->getDoctrine()->getManager()->flush();
            }

            $likeList = $this->getDoctrine()->getRepository(UserLike::class)->findBy(array('likeReceiver' => $sender, 'likeSender' => $receiver));
            if(sizeof($likeList) != 0){
                $this->getDoctrine()->getManager()->remove($likeList[0]);
                $this->getDoctrine()->getManager()->flush();
            }

            $block = new UserBlock();
            $block->setDate(new \DateTime());
            $block->setBlockReceiver($receiver);
            $block->setBlockSender($sender);
            $this->getDoctrine()->getManager()->persist($block);
            $this->getDoctrine()->getManager()->flush();

            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @Route("/seif/uploadPhoto", name="seif_uploadPhoto")
     */
    public function uploadPhotoAction(Request $request){
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->add("userId", null, array('mapped' => false));
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $user = $this->getDoctrine()->getRepository(User::class)->find((int)$form['userId']->getData());
            $photo->setUser($user);
            $photo->setType(\BaseBundle\Entity\Enumerations\PhotoType::Regular);
            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();

            $handler = $this->get('vich_uploader.templating.helper.uploader_helper');
            return new JsonResponse(array('id' => $photo->getId(), 'photoUri' =>  $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath().'/'.$handler->asset($photo, 'imageFile'), 'updateDate' => $photo->getDate()));
        }
        //return $this->render('@Service/Default/index.html.twig', array('form' => $form->createView()));
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/seif/getPhoto/{id}", name="seif_getPhoto")
     */
    public function getPhotoAction(Request $request, $id){
        $photo = $this->getDoctrine()->getRepository(Photo::class)->find($id);
        if($photo != null){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(1);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $handler = $this->get('vich_uploader.templating.helper.uploader_helper');
            $photo->setImage($request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath().'/'.$handler->asset($photo, 'imageFile'));
            $data = $serializer->normalize($photo);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @Route("/seif/getPhotos/{userId}", name="seif_getPhotos")
     */
    public function getPhotosAction(Request $request, $userId){
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        if($user!=null){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(1);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $photos = $this->getDoctrine()->getRepository(Photo::class)->findBy(array(
                'user' => $user,
                'type' => \BaseBundle\Entity\Enumerations\PhotoType::Regular
            ));
            $handler = $this->get('vich_uploader.templating.helper.uploader_helper');
            foreach ($photos as $photo){
                $photo->setImage($request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath().'/'.$handler->asset($photo, 'imageFile'));
            }
            $data = $serializer->normalize($photos);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $userId
     * @Route("/seif/getProfilePhoto/{userId}", name="seif_getProfilePhoto")
     */
    public function getProfilePhotoAction(Request $request, $userId){
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        if($user!=null){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(1);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $photos = $this->getDoctrine()->getRepository(Photo::class)->findBy(array(
                'user' => $user,
                'type' => \BaseBundle\Entity\Enumerations\PhotoType::Profile
            ));
            if(sizeof($photos)<=0){
                return new JsonResponse(null);
            }
            $handler = $this->get('vich_uploader.templating.helper.uploader_helper');
            $photos[0]->setImage($request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath().'/'.$handler->asset($photos[0], 'imageFile'));
            $data = $serializer->normalize($photos[0]);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $userId
     * @Route("/seif/getCoverPhoto/{userId}", name="seif_getCoverPhoto")
     */
    public function getCoverPhotoAction(Request $request, $userId){
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        if($user!=null){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(1);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $photos = $this->getDoctrine()->getRepository(Photo::class)->findBy(array(
                'user' => $user,
                'type' => \BaseBundle\Entity\Enumerations\PhotoType::Cover
            ));
            if(sizeof($photos)<=0){
                return new JsonResponse(null);
            }
            $handler = $this->get('vich_uploader.templating.helper.uploader_helper');
            $photos[0]->setImage($request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath().'/'.$handler->asset($photos[0], 'imageFile'));
            $data = $serializer->normalize($photos[0]);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $userId
     * @param $photoId
     * @Route("/seif/setAsProfilePhoto/{photoId}", name="seif_setAsProfile")
     */
    public function setAsProfilePictureAction(Request $request, $photoId){
        $photo = $this->getDoctrine()->getRepository(Photo::class)->find($photoId);
        if($photo != null){
            $oldProfile = $this->getDoctrine()->getRepository(Photo::class)->findBy(array(
                'user' => $photo->getUser(),
                'type' => \BaseBundle\Entity\Enumerations\PhotoType::Profile
            ));
            if(sizeof($oldProfile) > 0){
                $oldProfile[0]->setType(\BaseBundle\Entity\Enumerations\PhotoType::Regular);
                $this->getDoctrine()->getManager()->persist($oldProfile[0]);
                $this->getDoctrine()->getManager()->flush();
            }
            $photo->setType(\BaseBundle\Entity\Enumerations\PhotoType::Profile);
            $this->getDoctrine()->getManager()->persist($photo);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $userId
     * @param $photoId
     * @Route("/seif/setAsCoverPhoto/{photoId}", name="seif_setAsCover")
     */
    public function setAsCoverPictureAction(Request $request, $photoId){
        $photo = $this->getDoctrine()->getRepository(Photo::class)->find($photoId);
        if($photo != null){
            $oldProfile = $this->getDoctrine()->getRepository(Photo::class)->findBy(array(
                'user' => $photo->getUser(),
                'type' => \BaseBundle\Entity\Enumerations\PhotoType::Cover
            ));
            if(sizeof($oldProfile) > 0){
                $oldProfile[0]->setType(\BaseBundle\Entity\Enumerations\PhotoType::Regular);
                $this->getDoctrine()->getManager()->persist($oldProfile[0]);
                $this->getDoctrine()->getManager()->flush();
            }
            $photo->setType(\BaseBundle\Entity\Enumerations\PhotoType::Cover);
            $this->getDoctrine()->getManager()->persist($photo);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/seif/deletePhoto/{id}", name="seif_deletePhoto")
     */
    public function deletePhotoAction(Request $request, $id){
        $photo = $this->getDoctrine()->getRepository(Photo::class)->find($id);
        if($photo != null){
            $this->getDoctrine()->getManager()->remove($photo);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/seif/getUserLikes/{id}", name="seif_getUserLikes")
     */
    public function getUserLikesAction(Request $request, $id){
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if($user != null){
            $likes = $this->getDoctrine()->getRepository(UserLike::class)->findBy(array('likeSender' => $user));
            if(sizeof($likes)<=0){
                return new JsonResponse(null);
            }
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(1);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $data = $serializer->normalize($likes);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/seif/getUserLike/{senderId}/{receiverId}", name="seif_getUserLike")
     */
    public function getUserLikeAction(Request $request, $senderId, $receiverId){
        $sender = $this->getDoctrine()->getRepository(User::class)->find($senderId);
        $receiver = $this->getDoctrine()->getRepository(User::class)->find($receiverId);
        if($sender != null && $receiver != null){
            $likes = $this->getDoctrine()->getRepository(UserLike::class)->findBy(array('likeSender' => $sender, 'likeReceiver' => $receiver));
            if(sizeof($likes) <= 0){
                return new JsonResponse(null);
            }
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(1);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $data = $serializer->normalize($likes[0]);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $userId
     * @return Response
     * @Route("/seif/getUserBlocks/{userId}", name="seif_getUserBlocks")
     */
    public function getUserBlocksAction(Request $request, $userId){
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        if($user != null){
            $blocks = $this->getDoctrine()->getRepository(UserBlock::class)->findBy(array("blockSender" => $user));
            if(sizeof($blocks) <= 0){
                return new JsonResponse(null);
            }
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(1);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $data = $serializer->normalize($blocks);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $senderId
     * @param $receiverId
     * @return Response
     * @Route("/seif/getUserBlock/{senderId}/{receiverId}", name="seif_getBlock")
     */
    public function getBlockAction(Request $request, $senderId, $receiverId){
        $sender = $this->getDoctrine()->getRepository(User::class)->find($senderId);
        $receiver = $this->getDoctrine()->getRepository(User::class)->find($receiverId);
        if($sender != null && $receiver != null){
            $blocks = $this->getDoctrine()->getRepository(UserBlock::class)->findBy(array("blockSender" => $sender, "blockReceiver" => $receiver));
            if(sizeof($blocks) <= 0){
                return new JsonResponse(null);
            }
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(1);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $data = $serializer->normalize($blocks[0]);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }

    /**
     * @param Request $request
     * @param $senderId
     * @param $receiverId
     * @return Response
     * @Route("/seif/removeBlock/{senderId}/{receiverId}", name="seif_removeBlock")
     */
    public function removeBlockAction(Request $request, $senderId, $receiverId){
        $sender = $this->getDoctrine()->getRepository(User::class)->find($senderId);
        $receiver = $this->getDoctrine()->getRepository(User::class)->find($receiverId);
        if($sender != null && $receiver != null){
            $blocks = $this->getDoctrine()->getRepository(UserBlock::class)->findBy(array("blockSender" => $sender, "blockReceiver" => $receiver));
            if(sizeof($blocks) <= 0){
                return new Response(Response::HTTP_NOT_FOUND);
            }
            $this->getDoctrine()->getManager()->remove($blocks[0]);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_NOT_FOUND);
    }
}
