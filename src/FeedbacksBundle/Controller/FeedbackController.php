<?php

namespace FeedbacksBundle\Controller;

use BaseBundle\Entity\Feedback;
use BaseBundle\Repository\FeedbacksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Feedback controller.
 *
 * @Route("/")
 */
class FeedbackController extends Controller
{
    /**
     * Lists all feedback entities.
     *
     * @Route("index", name="feedback_index")
     * @Method("GET")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $feedbacks =$em->getRepository('BaseBundle:Feedback')->findAll();
        $state = $em->getRepository('BaseBundle:Feedback')->findBy(['state' => '0']);
        $state1 = $em->getRepository('BaseBundle:Feedback')->findBy(['state' => '1']);
        $tot= count($state);
        $tot1= count($state1);

        return $this->render('FeedbacksBundle:feedback:index.html.twig', array(
            'feedbacks' => $feedbacks, 'nb'=>$tot , 'nb1'=>$tot1
        ));
    }

    /**
     * Creates a new feedback entity.
     *
     * @Route("/new", name="feedback_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $feedback = new Feedback();
        $form = $this->createForm('BaseBundle\Form\FeedbackType', $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $senderId = $this->getUser()->getId();
            $sender = $em->getRepository('BaseBundle:User')->find($senderId);

            if (!$sender) {
                throw $this->createNotFoundException(
                    'No sender found for id '.$senderId
                );
            }
            $feedback->setState(false);
            $feedback->setSender($sender);
            $em->persist($feedback);
            $em->flush();

            return $this->redirectToRoute('news_feed');
        }

        return $this->render('FeedbacksBundle:feedback:new.html.twig', array(
            'feedback' => $feedback,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new feedback entity.
     *
     * @Route("/newFeed", name="feedback_add")
     */
    public function addFeedbackAction(Request $request){
        if($request->isXmlHttpRequest()){
            $feedback = new Feedback();
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $feedback->setContent($request->get('content'));
            $feedback->setState(false);
            $feedback->setSender($user);
            $this->getDoctrine()->getManager()->persist($feedback);
            $this->getDoctrine()->getManager()->flush();

            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }



    /**
     * Deletes a feedback entity.
     *
     * @Route("index/{id}", name="feedback_deletee")
     *
     */

    public function deleteFeedAction(Request $request)
    {
        $id=$request->get('id');
        $em=$this->getDoctrine()->getManager();
        $feedback = $em->getRepository('BaseBundle:Feedback')->find($id);
        $em->remove($feedback);
        $em->flush();
        return $this->redirectToRoute('feedback_index');
    }

    /**
     *  a feedback entity.
     *
     * @Route("index/edit/{id}", name="feedback_state")
     *
     */

    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $feedback= $em->getRepository('BaseBundle:Feedback')->find($id);
        $feedback->setState(true);
        $em->persist($feedback);
        $em->flush();

        return $this->redirectToRoute('feedback_index');
    }

    /**
     *  a feedback entity.
     *
     * @Route("pdf", name="feedback_pdf")
     *
     */

    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();

        $feedbacks = $em->getRepository('BaseBundle:Feedback')->findAll();

        $website  = $this->renderView('FeedbacksBundle:feedback:export.html.twig', array(
            'feedbacks' => $feedbacks,
        ));

        $snapper = $this->get('knp_snappy.pdf');
        // $snapper->setOption("encoding","UTF-8");

        $filename="file";
        /*$website  = $this->renderView('@OCPlatform/Sign/index.html.twig',array('signaux'=>$result,'form'=>$form_rech->createView()));*/

        return new Response(
            $snapper->getOutputFromHtml($website),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }
}
