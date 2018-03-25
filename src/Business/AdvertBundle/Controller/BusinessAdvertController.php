<?php

namespace Business\AdvertBundle\Controller;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BaseBundle\Form\AdvertType;
use PubliciteBundle\Entity\Advert2;
use PayPal\Api\Transaction;
use BaseBundle\Entity\Advert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use PubliciteBundle\Repository\AdvertRepository;

require 'C:\xampp\htdocs\mysoulmate\vendor\autoload.php';
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
    /**
     * @Route("/Success/{id}",name="success_business")
     */
    public function SuccessAction($id)
    {
        $ids = require('C:\xampp\htdocs\mysoulmate\src\Business\AdvertBundle\config.php');
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $ids['id'],$ids['secret']
            )
        );

        $payment = Payment::get($_GET['paymentId'],$apiContext);
        $execution = (new \PayPal\Api\PaymentExecution())->setPayerId($_GET['PayerID'])->setTransactions($payment->getTransactions());

        try {
            $payment->execute($execution,$apiContext);
            $idq = $payment->getTransactions()[0]->getCustom();
            if ($payment->getId()!=null)
            {
                $em=$this->getDoctrine()->getManager();
                $em->getRepository(\BaseBundle\Entity\Advert::class)->UpdateThisAddDQL($idq);

                $em->flush();
                return $this->redirectToRoute("business_adverts_list");
            }else { return $this->redirectToRoute("business_adverts_list");}

        }catch (PayPalConnectionException $ex) { var_dump(json_decode($ex->getData()));}
        return $this->redirectToRoute("business_adverts_list");
    }



    /**
     * @Route("/Payer/{id}",name="payer_business")
     */

    public function PayerAction($id)
    {
        $advert = new Advert();
        $repo = $this->getDoctrine()->getRepository(Advert::class);
        $advert=$repo->find($id);

        $ids = require('C:\xampp\htdocs\mysoulmate\src\Business\AdvertBundle\config.php');
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $ids['id'],$ids['secret']
            )
        );
        $price = $advert->getPrice();
        $list = new ItemList();
        $item = (new Item())->setName(''.$advert->getContent())->setPrice($price)->setCurrency("USD")
            ->setQuantity('1');
        $list->addItem($item);
        $details= (new Details())->setSubtotal($price);
        $amout = (new Amount())->setTotal($price)->setCurrency('USD')->setDetails($details);
        $transaction = (new Transaction())->setItemList($list)->setDescription('Buying advert space '.$advert->getContent())
        ->setAmount($amout)->setCustom(''.$advert->getId());

        $payment = new Payment();
        $payment->setTransactions([$transaction]);
        $payment->setIntent('sale');
        $redirecUrls = new RedirectUrls();
        $redirecUrls->setReturnUrl('http://localhost/mysoulmate/web/app_dev.php/business/adverts/Success/'.$payment->getTransactions()[0]->getCustom());
        $redirecUrls->setCancelUrl('http://localhost/mysoulmate/web/app_dev.php/business/adverts/Lister');

        $payment->setRedirectUrls($redirecUrls);
        $payment->setPayer((new Payer())->setPaymentMethod('paypal'));


        try {
            $payment->create($apiContext);

        }catch (PayPalConnectionException $ex) { var_dump(json_decode($ex->getData()));}
        return $this->redirect(''.$payment->getApprovalLink());
    }

}
