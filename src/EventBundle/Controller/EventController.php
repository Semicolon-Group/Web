<?php

namespace EventBundle\Controller;

use blackknight467\StarRatingBundle\Form\RatingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BaseBundle\Entity\Event;
use BaseBundle\Entity\Rating;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/", name="events")
     */

    public function eventsAction(Request $request)
    {
        $em=$this->getDoctrine();
        $rating = $em->getRepository('BaseBundle:Rating')->avgrating();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $event=$em->getRepository(Event::class)->findBy(array(
            'state'=>['Approved' => '1']
        ));
        if($request->isMethod('POST')){
            $title=$request->get('title');
            $event=$em->getRepository("BaseBundle:Event")->findEventByName($title);
        }
        $data = [];
        foreach ($event as $e){
            $exists = false;
            foreach ($e->getUser() as$user){
                if($user->getId() == $user->getId()){
                    $exists = true;
                }
            }
            array_push($data, ['event' => $e, 'exists' => $exists]);
        }

        return $this->render('EventBundle:Event:events.html.twig', array(
            'events'=>$data,
            'rating'=>$rating

        ));
    }

    /**
     * @Route("/info/{id}", name="info_event")
     */
    public function infoAction($id,Request $request){

        $rating = new Rating();
        $m = $this->getDoctrine()->getManager();
        $mark = $m->getRepository('BaseBundle:Event')->find($id);
        $form = $this->createFormBuilder($rating)
            ->add('rating', RatingType::class, [
                'label' => 'Rating'
            ])
            ->add('valider', SubmitType::class, array(
                'attr' => array(

                    'class' => 'btn btn-xs btn-primary'
                )))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setIdE($mark->getId());
            $m->persist($rating);
            $m->flush();
        }

        $data=array(
            'm' => $mark,
            'f' => $form->createView()
        );
        return $this->render('EventBundle:Event:info.html.twig',$data);
    }


    /**
     * @Route("/participate", name="participate_event")
     */
    public function participateAction(Request $request){
        $event = $this->getDoctrine()->getRepository(Event::class)->find($request->get('id'));
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $event->getUser()->add($user);
        $this->getDoctrine()->getManager()->persist($event);
        $this->getDoctrine()->getManager()->flush();
        //create your collection
        //if created $success = true
        return new JsonResponse(['success' => true]);
    }

    /**
     * @Route("/unparticipate", name="unparticipate_event")
     */
    public function unParticipateAction(Request $request){
        $event = $this->getDoctrine()->getRepository(Event::class)->find($request->get('id'));
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $event->removeUser($user);
        $this->getDoctrine()->getManager()->persist($event);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse(['success' => true]);
    }



}
