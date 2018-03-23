<?php

namespace EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BaseBundle\Entity\Event;


class EventController extends Controller
{
    /**
     * @Route("/", name="events")
     */

    public function eventsAction()
    {
        $em=$this->getDoctrine();
        $event=$em->getRepository(Event::class)->findAll();
        return $this->render('EventBundle:Event:events.html.twig', array('events'=>$event));
    }






}
