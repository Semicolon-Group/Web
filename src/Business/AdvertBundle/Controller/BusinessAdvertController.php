<?php

namespace Business\AdvertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BaseBundle\Form\AdvertType;
use PubliciteBundle\Entity\Advert2;

use BaseBundle\Entity\Advert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use PubliciteBundle\Repository\AdvertRepository;

class BusinessAdvertController extends Controller
{
    /**
     * @Route("/Afficher")
     */
    public function AfficherAction()
    {
        $var2 =$this->getDoctrine()->getRepository(Advert::class)->findBigPubDQL();
        $var =$this->getDoctrine()->getRepository(Advert::class)->findSidePubDQL();
        return $this->render('BusinessAdvertBundle:BusinessAdvert:afficher.html.twig', array(
            'sides'=>$var,
            'bigs'=>$var2
        ));
    }

    /**
     * @Route("/Ajouter",name="business_adverts_add")
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
            return $this->redirectToRoute('business_adverts_list');
        }
        return $this->render('BusinessAdvertBundle:BusinessAdvert:ajouter.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/Lister",name="business_adverts_list")
     */
    public function ListerAction()
    {
        $user = $this->container
            ->get('security.token_storage')
            ->getToken()->getUser()->getId();

        $var =$this->getDoctrine()->getRepository(Advert::class)->findThisUserPubs($user);
        return $this->render('BusinessAdvertBundle:BusinessAdvert:lister.html.twig', array(
            'pubs'=>$var
        ));
    }

    /**
     * @Route("/Modifier/{id}",name="traiter_business")
     */
    public function ModifierAction(Request $request , $id)
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
            ],'attr' => array(

                    'hidden'=>true
                )]
            )
            ->
            add('Valider',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("business_adverts_list");
        }
        return $this->render('BusinessAdvertBundle:BusinessAdvert:modifier.html.twig', array(
            'form'=>$form->createView()
        ));
    }

}
