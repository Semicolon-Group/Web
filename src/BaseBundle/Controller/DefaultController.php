<?php

namespace BaseBundle\Controller;

use BaseBundle\Entity\Answer;
use BaseBundle\Form\AnswerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $this->getDoctrine()->getManager()->persist($answer);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('BaseBundle:Default:index.html.twig', array('form' => $form->createView()));
    }

    public function displayAnswerAction(){
        $answers = $this->getDoctrine()->getRepository(Answer::class)->findAll();
        return $this->render('@Base/Default/affichage.html.twig', array('answers' => $answers));
    }
}
