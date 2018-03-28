<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BaseBundle\Entity\Event;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/", name="events")
     */

    public function eventsAction()
    {
        $em=$this->getDoctrine();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $event=$em->getRepository(Event::class)->findAll();
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
        return $this->render('EventBundle:Event:events.html.twig', array('events'=>$data));
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
