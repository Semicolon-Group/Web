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
use BaseBundle\Entity\UserBlock;
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
            'form' => $form->createView(),
            'available_questions_count' => $this->getAvailableQuestionCount(),
            'diffs' => $this->getMandatory()
        ));
    }

    private function getMandatory(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $data=null;
        if(sizeof($this->getDoctrine()->getRepository(Answer::class)->getMandatoryAnswers($user))<10){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(2);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('user' => $user));
            $answeredQuestions = array_map(function ($an){
                return $an->getQuestion();
            }, $answers);
            $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(array("topic" => Topic::Mandatory));

            if(sizeof($answeredQuestions) != sizeof($questions)){
                $diffQuestions = array_udiff($questions, $answeredQuestions,
                    function ($obj_a, $obj_b) {
                        return $obj_a->getId() - $obj_b->getId();
                    }
                );
                $diffQuestions = array_values($diffQuestions);
                if(sizeof($diffQuestions) != 0){
                    $questionIndex = rand ( 0 , sizeof($diffQuestions)-1 );
                    $question = $diffQuestions[$questionIndex];
                    $choices = $this->getDoctrine()->getRepository(Choice::class)->findBy(array('question' => $question));
                    $toSend = array('question' => $question, 'choices' => $choices);
                    $data = $serializer->normalize($toSend);
                }
                return $diffQuestions;
            }
        }
        return null;
    }

    /**
     * @Route("/answer/generate_mandatory", name="generate_mandatory_answer");
     */
    public function getMandatoryQuestions(Request $request){
        if($request->isXmlHttpRequest()){
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $data=null;
            if(sizeof($this->getDoctrine()->getRepository(Answer::class)->getMandatoryAnswers($user))<10){
                $normalizer = new ObjectNormalizer();
                $normalizer->setCircularReferenceLimit(2);
                // Add Circular reference handler
                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });
                $serializer = new Serializer(array($normalizer));
                $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('user' => $user));
                $answeredQuestions = array_map(function ($an){
                    return $an->getQuestion();
                }, $answers);
                $questions = $this->getDoctrine()->getRepository(Question::class)->findBy(array("topic" => Topic::Mandatory));

                if(sizeof($answeredQuestions) != sizeof($questions)){
                    $diffQuestions = array_udiff($questions, $answeredQuestions,
                        function ($obj_a, $obj_b) {
                            return $obj_a->getId() - $obj_b->getId();
                        }
                    );
                    $diffQuestions = array_values($diffQuestions);
                    if(sizeof($diffQuestions) != 0){
                        $questionIndex = rand ( 0 , sizeof($diffQuestions)-1 );
                        $question = $diffQuestions[$questionIndex];
                        $choices = $this->getDoctrine()->getRepository(Choice::class)->findBy(array('question' => $question));
                        $toSend = array('question' => $question, 'choices' => $choices);
                        $data = $serializer->normalize($toSend);
                    }
                }
            }
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getAvailableQuestionCount(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('user' => $user));
        $answeredQuestions = array_map(function ($an){
            return $an->getQuestion();
        }, $answers);
        $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();

        $diffQuestions = array();
        if(sizeof($answeredQuestions) != sizeof($questions)){
            $diffQuestions = array_udiff($questions, $answeredQuestions,
                function ($obj_a, $obj_b) {
                    return $obj_a->getId() - $obj_b->getId();
                }
            );
        }
        return sizeof($diffQuestions);
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
     * @Route("/profile/updateAbout", name="update_about")
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
     * @Route("/profile/other/{id}", name="other_profile")
     */
    public function otherProfileAction($id){
        $connectedUser = $this->container->get('security.token_storage')->getToken()->getUser();

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('user'=>$user));
        $photos = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('user'=>$user, 'type' => \BaseBundle\Entity\Enumerations\PhotoType::Regular));
        $coverList = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('user'=>$user, 'type' => \BaseBundle\Entity\Enumerations\PhotoType::Cover));
        $profileList = $this->getDoctrine()->getRepository(Photo::class)->findBy(array('user'=>$user, 'type' => \BaseBundle\Entity\Enumerations\PhotoType::Profile));
        $likesList = $this->getDoctrine()->getRepository(UserLike::class)->findBy(array('likeReceiver' => $user, 'likeSender' => $connectedUser));

        if(sizeof($likesList)!=0){
            $like = $likesList[0];
        }else{
            $like = null;
        }

        return $this->render("@Member/Member/otherProfile.html.twig", array(
            'user' => $user,
            'answers' => $answers,
            'topics' => Topic::getEnumAsArray(),
            'importances' => Importance::getEnumAsArray(),
            'photos' => $photos,
            'cover' => sizeof($coverList)==0?null:$coverList[0],
            'profile' => sizeof($profileList)==0?null:$profileList[0],
            'like' => $like
        ));
    }

    /**
     * @Route("/profile/like", name="like_profile")
     */
    public function likeProfileAction(Request $request){
        if($request->isXmlHttpRequest()){
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $usertoLike = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
            $like = new UserLike();
            $like->setLikeSender($user);
            $like->setLikeReceiver($usertoLike);
            $like->setDate(new \DateTime());
            $this->getDoctrine()->getManager()->persist($like);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/profile/dislike", name="dislike_profile")
     */
    public function dislikeProfileAction(Request $request){
        if($request->isXmlHttpRequest()){
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $usertoDislike = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
            $like = $this->getDoctrine()->getRepository(UserLike::class)->findBy(array('likeSender' => $user, 'likeReceiver' => $usertoDislike))[0];
            $this->getDoctrine()->getManager()->remove($like);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/profile/block/{id}", name="block_profile")
     */
    public function blockProfileAction($id){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $usertoBlock = $this->getDoctrine()->getRepository(User::class)->find($id);

        $likeList = $this->getDoctrine()->getRepository(UserLike::class)->findBy(array('likeReceiver' => $usertoBlock, 'likeSender' => $user));
        if(sizeof($likeList) != 0){
            $this->getDoctrine()->getManager()->remove($likeList[0]);
            $this->getDoctrine()->getManager()->flush();
        }

        $likeList = $this->getDoctrine()->getRepository(UserLike::class)->findBy(array('likeReceiver' => $user, 'likeSender' => $usertoBlock));
        if(sizeof($likeList) != 0){
            $this->getDoctrine()->getManager()->remove($likeList[0]);
            $this->getDoctrine()->getManager()->flush();
        }

        $block = new UserBlock();
        $block->setDate(new \DateTime());
        $block->setBlockReceiver($usertoBlock);
        $block->setBlockSender($user);
        $this->getDoctrine()->getManager()->persist($block);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('member_profile');
    }
}
