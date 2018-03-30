<?php

namespace Business\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BaseBundle\Entity\Event;
use BaseBundle\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="business_events")
     */
    public function indexAction()
    {
        $em=$this->getDoctrine();
        $event=$em->getRepository(Event::class)->findAll();
        return $this->render('BusinessEventBundle:Default:index.html.twig', array(
            'events'=>$event,
            'user'=>$this->getUser(),
        ));
    }

    /**
     * @Route("/Delete/{id}", name="business_events_delete")
     */
    public function DeleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->find($id);
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('business_events',array('event' => $event));
    }

    /**
     * @Route("/create", name="business_create_event")
     */
    public function createAction(Request $request){
        $event = new Event();
        /*$event->setUser($this->getUser());*/
        $form= $this->createForm(EventType::class,$event)
            /*->add('photoUrl',FileType::class, ['required' => true , 'data_class' => null])*/
            ->add('Valider',SubmitType::class);
        $form = $this->createForm('BaseBundle\Form\EventType', $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->container
                ->get('security.token_storage')
                ->getToken()->getUser();
            $em = $this->getDoctrine()->getManager();
            $file = $event->getPhotoUrl();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('event_directory'),
                $fileName
            );
            $event->setPhotoUrl($fileName);
            $em->persist($event);
            $em->flush();

        }
        return $this->render('BusinessEventBundle:Default:create.html.twig', array(
            'events'=>$event,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/Edit/{id}", name="business_events_edit")
     */
    public function EditAction(Request $request, event $event)
    {

        $editForm = $this->createForm('BaseBundle\Form\EventType', $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $file = $event->getPhotoUrl();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('event_directory'),
                $fileName
            );
            $event->setPhotoUrl($fileName);
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('business_events',array('event' => $event));
        }

        return $this->render('BusinessEventBundle:Default:edit.html.twig', array('event' => $event,
            'edit_form' => $editForm->createView(),

        ));
    }
}
