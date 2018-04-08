<?php

namespace Admin\BusinessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BaseBundle\Entity\Event;
use BaseBundle\Form\EventType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Swift_Message;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class EventAdminController extends Controller
{

    /**
     * @Route("/index", name="admin_businesses")
     */
    public function indexAction()
    {
        $em=$this->getDoctrine();
        $event=$em->getRepository(Event::class)->findAll();
        $var1 =$em->getRepository('BaseBundle:Event')->findNotifsEventsAdmin();
        return $this->render('AdminBusinessBundle:AdminEvent:adminevent.html.twig', array(
            'events'=>$event,
            'user'=>$this->getUser(),
            'notifs'=>$var1,

        ));
    }

    /**
     * @Route("/Edit/{id}", name="Admin_events_edit")
     */
    public function EditAction(Request $request,event $event,$id)
    {
        $advert = new Event();
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $var2 =$this->getDoctrine()->getRepository(Event::class)->findNotifsEventsAdmin();
        $event=$repo->find($id);
        $x = $event->getState() ;
        $editForm = $this->createForm('BaseBundle\Form\EventType', $event)->add('state', ChoiceType::class,[
            'choices' => [ 'Approved' => '1',  'Not processed' => '0', 'Denied'=>'2' ]
        ])
            ->add('reason',TextType::class, ['required' => true])->add('Valider',SubmitType::class);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $var1 =$this->getDoctrine()->getRepository(Event::class)->find($id);
            if ($var1->getState() != $x && $var1->getState()== 1  ) {
                /*$message = (new Swift_Message())
                    ->setSubject('MySoulmate | Add approved !')
                    ->setFrom('mysoulmatepi@gmail.com')
                    ->setTo('haithem.besghaier@esprit.tn')
                    ->setBody(
                        "Bonjour Monsieur  Nous avons confirmé votre demande de pub !
                    Vous pouvez maintenant la payer afin qu'elle soit publiée sur notre Site !             
                     "
                        ." Votre . ",
                        'text/html'
                    );
                $this->get('mailer')->send($message);*/
            }var_dump($var1->getState());
            return $this->redirectToRoute("admin_businesses");
        }


        return $this->render('AdminBusinessBundle:AdminEvent:Edit_event_admin.html.twig', array('event' => $event,
            'edit_form' => $editForm->createView(),'notifs'=>$var2,

        ));
    }

    /**
     * @Route("/Delete", name="admin_events_delete")
     */
    public function DeleteAction(Request $request)
    {
        if($request->isXmlHttpRequese()){
            $id = $request->get('id');
            $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush();
            return new JsonResponse();
        }
    }
}
