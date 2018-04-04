<?php

namespace Admin\BusinessBundle\Controller;

use BaseBundle\Entity\Promotion;
use BaseBundle\Entity\User;
use BaseBundle\Form\PromotionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BaseBundle\Entity\Advert;
use BaseBundle\Form\AdvertType;
use Swift_Message;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $var1 =$this->getDoctrine()->getRepository(Advert::class)->findNotifsAdmin();
        $var2 =$this->getDoctrine()->getRepository(Advert::class)->findPubsPourAdminDQL();

        return $this->render('AdminBusinessBundle:Advert:lister.html.twig', array(
            'pubs'=>$var2,
            'notifs'=>$var1,

        ));
    }

    /**
     * @Route("/Traiter/{id}" , name="traiter_admin")
     */
    public function TraiterAction(Request $request , $id)
    {

        $var2 =$this->getDoctrine()->getRepository(Advert::class)->findNotifsAdmin();
        $advert = new Advert();
        $repo = $this->getDoctrine()->getRepository(Advert::class);
        $advert=$repo->find($id);
         $x = $advert->getState() ;
        $form=$this->createForm(AdvertType::class,$advert)->add('state', ChoiceType::class,[
            'choices' => [ 'Approved' => '1',  'Not processed' => '0', 'Denied'=>'2' ]
        ])->
        add('videoUrl',TextType::class, [ 'attr' => array(

            'required' => false

        )])
            ->add('payed',ChoiceType::class,[
            'choices' => ['Payed' => '1', 'Not payed' => '0']
        ])->add('reason',TextType::class, ['required' => true]) ->add('Valider',SubmitType::class);;
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
            'form'=>$form->createView(),'ad'=>$advert,'notifs'=>$var2,
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
    /**
     * @Route("/AjouterPromotion",name="admin_promotion_add")
     */
    public function AjouterPromoAction(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class,$promotion)->add('Valider',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {

            $em=$this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();
            $users = $this->getDoctrine()->getRepository(User::class)->findAll();
            foreach ($users as $user )
            {

                $message = (new Swift_Message())
                    ->setSubject('MySoulmate | Discount !')
                    ->setFrom('mysoulmatepi@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody("Hi for our special occasion ".$promotion->getName()." , this discount code will give you 30% of discount on your next purchases : ".$promotion->getCode()." , Ejoy it !" ,

                        'text/html'
                    );
                $this->get('mailer')->send($message);
            }
            return $this->redirectToRoute('admin_promotions_list');

        }
        return $this->render('AdminBusinessBundle:Advert:ajouterPromotion.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/PromotionsList",name="admin_promotions_list")
     */
    public function ListerPromosAction()
    {

        $var2 =$this->getDoctrine()->getRepository(Promotion::class)->findAll();

        return $this->render('AdminBusinessBundle:Advert:listerpromos.html.twig', array(
            'promos'=>$var2,
        ));
    }

    /**
     * @Route("/SupprimerPromoAdmin" , name="supprimer_promo_admin")
     */
    public function deletePostAction(Request $request){
        if($request->isXmlHttpRequest()){
            $id = $request->request->get('id');
            $promotion = new Promotion();
            $repo = $this->getDoctrine()->getRepository(Promotion::class);
            $promotion = $repo->find($id);
            $em=$this->getDoctrine()->getManager();
            $em->remove($promotion);
            $em->flush();
            return new JsonResponse();
        }
    }


    /**
     * @Route("/TraiterPromo/{id}" , name="traiter_promo_admin")
     */
    public function TraiterPromoAction(Request $request , $id)
    {


        $promotion = new Promotion();
        $repo = $this->getDoctrine()->getRepository(Promotion::class);
        $promotion=$repo->find($id);

        $form=$this->createForm(PromotionType::class,$promotion)

            ->add('Valider',SubmitType::class);;
        $form->handleRequest($request);
        if ($form->isSubmitted() )
        {


            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('admin_promotions_list');

        }
        return $this->render('AdminBusinessBundle:Advert:modifierpromos.html.twig', array(
            'form'=>$form->createView(),'promos'=>$promotion
        ));
    }
}
