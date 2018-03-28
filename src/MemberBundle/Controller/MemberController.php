<?php

namespace MemberBundle\Controller;

use BaseBundle\Entity\Answer;
use BaseBundle\Entity\Choice;
use BaseBundle\Entity\Enumerations\Importance;
use BaseBundle\Entity\Enumerations\Topic;
use BaseBundle\Entity\Photo;
use BaseBundle\Entity\PreferedRelation;
use BaseBundle\Entity\PreferedStatus;
use BaseBundle\Entity\Question;
use BaseBundle\Entity\User;
use BaseBundle\Entity\UserLike;
use BaseBundle\Form\AnswerType;
use BaseBundle\Form\PhotoType;
use BaseBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MemberController extends Controller
{
    /**
     * @Route("/profile", name="member_profile")
     */
    public function profileAction(Request $request)
    {
        if (!($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')))
            return $this->redirectToRoute('homepage');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $user->setPreferedRelations($this->getDoctrine()->getRepository(PreferedRelation::class)->findBy(array('user' => $user)));
        $user->setPreferedStatuses($this->getDoctrine()->getRepository(PreferedStatus::class)->findBy(array('user' => $user)));
        $likes = $this->getDoctrine()->getRepository(UserLike::class)->findBy(array('likeSender' => $user));
        foreach ($likes as $like){
            $pp = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('user' => $like->getLikeReceiver(), 'type' => \BaseBundle\Entity\Enumerations\PhotoType::Profile));
            if(sizeof($pp) != 0)
                $like->getLikeReceiver()->setProfilePhoto($pp[0]);
            else
                $like->getLikeReceiver()->setProfilePhoto(null);
        }

        $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('user'=>$user));
        $photos = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('user'=>$user, 'type' => \BaseBundle\Entity\Enumerations\PhotoType::Regular));
        $coverList = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('user'=>$user, 'type' => \BaseBundle\Entity\Enumerations\PhotoType::Cover));
        $profileList = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('user'=>$user, 'type' => \BaseBundle\Entity\Enumerations\PhotoType::Profile));

        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        return $this->render('MemberBundle:Member:profile.html.twig', array(
            'user' => $user,
            'likes' => $likes,
            'answers' => $answers,
            'topics' => Topic::getEnumAsArray(),
            'importances' => Importance::getEnumAsArray(),
            'photos' => $photos,
            'cover' => sizeof($coverList)==0?null:$coverList[0],
            'profile' => sizeof($profileList)==0?null:$profileList[0],
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/profile/upload", name="upload_photo")
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
     * @Route("/profile/edit", name="member_profile_edit")
     */
    public function editProfileAction(Request $request){
        if (!($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')))
            return $this->redirectToRoute('homepage');

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            /*$session = $this->getRequest()->getSession();
            $session->getFlashBag()->add('message', 'Successfully updated');
            $url = $this->generateUrl('matrix_edi_viewUser');
            $response = new RedirectResponse($url);*/

        }

        return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/update/about", name="update_about")
     */
    public function editAboutAction(Request $request){
        if($request->isXmlHttpRequest()){
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $user->setAbout($request->get('about'));
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/other/{id}", name="other_profile")
     */
    public function otherProfileAction($id){
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        return $this->render("@Member/Member/otherProfile.html.twig", array(
            'user' => $user
        ));
    }

    /**
     * @Route("/photos", name="get_photos")
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

    /**
     * @Route("/answer/generate", name="generate_answer")
     */
    public function generateAnswerAction(Request $request){
        if($request->isXmlHttpRequest()){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(2);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('user' => $user));
            $answeredQuestions = array_map(function ($an){
                return $an->getQuestion();
            }, $answers);
            $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();

            if(sizeof($answeredQuestions) != sizeof($questions)){
                foreach ($answeredQuestions as $aq){
                    for($i=0; $i<sizeof($questions); $i++){
                        if($questions[$i]->getId() == $aq->getId()){
                            unset($questions[$i]);
                            break;
                        }
                    }
                }


                $questions = array_values($questions);
                $questionIndex = rand ( 0 , sizeof($questions)-1 );
                $question = $questions[$questionIndex];
                $choices = $this->getDoctrine()->getRepository(Choice::class)->findBy(array('question' => $question));
                $toSend = array('question' => $question, 'choices' => $choices);
                $data = $serializer->normalize($toSend);

            }else{
                $data = null;
            }
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/answers", name="get_answers");
     */
    public function getAnswersAction(Request $request){
        if($request->isXmlHttpRequest()){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(2);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('user'=>$user));
            if(sizeof($answers)==0){
                return null;
            }else{
                $data = $serializer->normalize($answers);
                return new JsonResponse($data);
            }
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }



    /**
     * @Route("/answers/add", name="add_answer");
     */
    public function addAnswerAction(Request $request){
        if($request->isXmlHttpRequest()){
            $dom = $request->get('answer');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $answer = new Answer();
            $answer->setUser($user);
            $answer->setQuestion($this->getDoctrine()->getRepository(Question::class)->find($dom['question_id']));
            $answer->setSelectedChoice($this->getDoctrine()->getRepository(Choice::class)->find($dom['selected_choice_id']));
            $answer->setImportance($dom['importance_id']);
            $answer->setDate(new \DateTime());

            $acceptableChoices = $dom['accepted_choices_ids'];
            foreach ($acceptableChoices as $c){
                $answer->addChoice($this->getDoctrine()->getRepository(Choice::class)->find($c));
            }

            $this->getDoctrine()->getManager()->persist($answer);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
