<?php

namespace PubliciteBundle\Controller;

use BaseBundle\Form\AdvertType;
use PubliciteBundle\Entity\Advert2;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BaseBundle\Entity\Advert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use PubliciteBundle\Repository\AdvertRepository;
class AdvertController extends Controller
{

    /**
     * @Route("/Afficher",name="afficher_pub_1")
     */
    public function AfficherAction()
    {  $var2 =$this->getDoctrine()->getRepository(Advert::class)->findBigPubDQL();
        $var =$this->getDoctrine()->getRepository(Advert::class)->findSidePubDQL();
        return $this->render('PubliciteBundle:Advert:afficher.html.twig', array(
            'sides'=>$var,
             'bigs'=>$var2

        ));
    }


    /**
     * @Route("/Ajouter",name="Ajouter")
     */
    public function AjouterAction(Request $request)
    {

        $advert = new Advert();
        $form = $this->createForm(AdvertType::class,$advert)
            ->
            add('position',ChoiceType::class,[
                'choices' => [
                    'No'=>'0',
                    'TOP' => '1',
                    'Side' => '2',
                    'Bottom'=>'3'
                ],
                    'attr' => array(
                        'readonly' => true,
                        'hidden'=>true
                    )])
            ->
            add('Valider',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            $user = $this->container
            ->get('security.token_storage')
            ->getToken()->getUser();
            $em=$this->getDoctrine()->getManager();

            $advert->setBusiness($user);
            $em->persist($advert);
            $em->flush();
            return $this->redirectToRoute('Lister');
        }

        return $this->render('PubliciteBundle:Advert:ajouter.html.twig', array(
        'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/Lister",name="Lister")
     */
    public function ListerAction()
    {
        $user = $this->container
            ->get('security.token_storage')
            ->getToken()->getUser()->getId();

        $var =$this->getDoctrine()->getRepository(Advert::class)->findThisUserPubs($user);
        return $this->render('PubliciteBundle:Advert:lister.html.twig', array(
           'pubs'=>$var
        ));
    }
    /**
     * @Route("/Traiter/{id}" , name="traiter")
     */
    public function TraiterAction(Request $request , $id)
    {
        $advert = new Advert();
        $repo = $this->getDoctrine()->getRepository(Advert::class);
        $advert=$repo->find($id);
        $form=$this->createForm(AdvertType::class,$advert)->
        add('position',ChoiceType::class,[
            'choices' => [
                'No'=>'0',
                'TOP' => '1',
                'Side' => '2',
                'Bottom'=>'3'
            ],
        'disabled'=>true])
            ->
            add('Valider',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("Lister");
        }
        return $this->render('PubliciteBundle:Advert:traiter.html.twig', array(
            'form'=>$form->createView()
        ));
    }


}
