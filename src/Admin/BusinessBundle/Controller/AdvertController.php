<?php

namespace Admin\BusinessBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BaseBundle\Entity\Advert;
use BaseBundle\Form\AdvertType;
use Swift_Message;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class AdvertController extends Controller
{
    /**
     * @Route("/ListerAdmin",name="lister_admin")
     */
    public function ListerAction()
    {
        $var2 =$this->getDoctrine()->getRepository(Advert::class)->findPubsPourAdminDQL();
        return $this->render('AdminBusinessBundle:Advert:lister.html.twig', array(
            'pubs'=>$var2,
        ));
    }

    /**
     * @Route("/Traiter/{id}" , name="traiter_admin")
     */
    public function TraiterAction(Request $request , $id)
    {


        $advert = new Advert();
        $repo = $this->getDoctrine()->getRepository(Advert::class);
        $advert=$repo->find($id);
         $x = $advert->getState() ;
        $form=$this->createForm(AdvertType::class,$advert)->add('state', ChoiceType::class,[
            'choices' => [ 'Approved' => '1',  'Not processed' => '0', 'Denied'=>'2' ]
        ])->add('payed',ChoiceType::class,[
            'choices' => ['Payed' => '1', 'Not payed' => '0']
        ])->add('Valider',SubmitType::class);;
        $form->handleRequest($request);
        if ($form->isSubmitted() )
        {


            $em=$this->getDoctrine()->getManager();
            $em->flush();
            $var1 =$this->getDoctrine()->getRepository(Advert::class)->find($id);
            if ($var1->getState() != $x && $var1->getState()== 1  ) {

                $message = (new Swift_Message())
                    ->setSubject('MySoulmate | Add approved !')
                    ->setFrom('mysoulmatepi@gmail.com')
                    ->setTo($advert->getBusiness()->getEmail())
                    ->setBody(
                        "Bonjour Monsieur " .$advert->getBusiness()->getFirstName() . " ,  Nous avons confirmé votre demande de pub ! 
                    Vous pouvez maintenant la payer afin qu'elle soit publiée sur notre Site !             
                     "
                        ." Votre facture s'élève à ".$advert->getPrice()." EUR payable sur notre interface backoffice . ",

                        'text/html'
                    );
                $this->get('mailer')->send($message);
            }var_dump($var1->getState());
            return $this->redirectToRoute("lister_admin");

        }
        return $this->render('AdminBusinessBundle:Advert:modifier.html.twig', array(
            'form'=>$form->createView()
        ));
    }
    /**
     * @Route("/Supprimer/{id}" , name="supprimer_admin")
     */
    public function SupprimerAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $advert=$em->getRepository(Advert::class)->find($id);

        $em->remove($advert);
        $em->flush();
        return $this->redirectToRoute("lister_admin");

    }
}
