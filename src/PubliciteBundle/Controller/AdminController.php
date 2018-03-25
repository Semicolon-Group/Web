<?php

namespace PubliciteBundle\Controller;

use BaseBundle\Entity\Advert;
use BaseBundle\Form\AdvertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/ListerAdmin",name="lister_admin")
     */
    public function ListerAction()
    {
        $var2 =$this->getDoctrine()->getRepository(Advert::class)->findPubsPourAdminDQL();
        return $this->render('PubliciteBundle:Admin:lister.html.twig', array(
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
        $form=$this->createForm(AdvertType::class,$advert)->add('state', ChoiceType::class,[
            'choices' => [
                'Approved' => '1',
                'Not processed' => '0'
            ]
        ])->add('payed',ChoiceType::class,[
        'choices' => [
            'Payed' => '1',
            'Not payed' => '0'
        ]
    ])
        ->add('position',ChoiceType::class,[
            'choices' => [
                'TOP' => '1',
                'Side' => '2',
                'Bottom'=>'3'
            ]
        ] )
            ->
            add('Valider',SubmitType::class);;
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("lister_admin");
        }
        return $this->render('PubliciteBundle:Admin:traiter.html.twig', array(
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
