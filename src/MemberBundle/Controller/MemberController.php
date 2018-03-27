<?php

namespace MemberBundle\Controller;

use BaseBundle\Entity\Answer;
use BaseBundle\Entity\Enumerations\Topic;
use BaseBundle\Entity\PreferedRelation;
use BaseBundle\Entity\PreferedStatus;
use BaseBundle\Entity\User;
use BaseBundle\Entity\UserLike;
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
    public function profileAction()
    {
        if (!($this->container->get('security.authorization_checker')->isGranted('ROLE_USER')))
            return $this->redirectToRoute('homepage');
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $user->setPreferedRelations($this->getDoctrine()->getRepository(PreferedRelation::class)->findBy(array('user' => $user)));
        $user->setPreferedStatuses($this->getDoctrine()->getRepository(PreferedStatus::class)->findBy(array('user' => $user)));
        $likes = $this->getDoctrine()->getRepository(UserLike::class)->findBy(array('likeSender' => $user));
        $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('user'=>$user));
        return $this->render('MemberBundle:Member:profile.html.twig', array(
            'user' => $user,
            'likes' => $likes,
            'answers' => $answers,
            'topics' => Topic::getEnumAsArray()
        ));
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
            $serializer = new Serializer(array(new ObjectNormalizer()));
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
}
