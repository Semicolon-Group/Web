<?php

namespace SignalBundle\Controller;

use BaseBundle\Entity\User;
use BaseBundle\Entity\UserSignal;
use BaseBundle\Form\RechercheType;
use BaseBundle\Form\UserSignalType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Usersignal controller.
 *
 * @Route("/")
 */
class UserSignalController extends Controller
{
    /**
     * Lists all userSignal entities.
     *
     * @Route("index", name="Signal_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $req)
    {
        $signal = new UserSignal();
        $formRech = $this->createForm('BaseBundle\Form\RechercheType', $signal);
        $formRech->handleRequest($req);


        if ($formRech->isSubmitted() && $formRech->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $userSignals = $em->getRepository('BaseBundle:UserSignal')->findBy(array('reason' => $signal->getReason()));
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $userSignals, /* query NOT result */
                $req->query->getInt('page', 1)/*page number*/,
                5);/*limit per page*/
            //for delete buttons
            $deleteForms = array();
            foreach ($pagination as $signal) {
                $deleteForms[$signal->getId()] = $this->createDeleteForm($signal)->createView();
            }

            //for block buttons
            $blockForms = array();
            foreach ($pagination as $signal) {
                $idReceiver = $signal->getReceiver()->getId();
                $em = $this->getDoctrine()->getManager();
                $receiver = $em->getRepository('BaseBundle:User')->find($idReceiver);
                $blockForms[$idReceiver] = $this->createReceiverForm($receiver)->createView();
            }
            return $this->render('SignalBundle:usersignal:index.html.twig', array(
                'userSignals' => $pagination, 'form' => $formRech->createView(),
                'deleteForm' => $deleteForms,
                'blockForms' => $blockForms,

            ));
        }


        $em = $this->getDoctrine()->getManager();
        $userSignals = $em->getRepository('BaseBundle:UserSignal')->findAll();
        $state = $em->getRepository('BaseBundle:UserSignal')->findBy(['state' => '0']);
        $state1 = $em->getRepository('BaseBundle:UserSignal')->findBy(['state' => '1']);
        $tot= count($state);
        $tot1= count($state1);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $userSignals, /* query NOT result */
            $req->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        //for delete buttons
        $deleteForms = array();
        foreach ($pagination as $signal) {
            $deleteForms[$signal->getId()] = $this->createDeleteForm($signal)->createView();
        }

        //for block buttons
        $blockForms = array();
        foreach ($pagination as $signal) {
            $idReceiver = $signal->getReceiver()->getId();
            $em = $this->getDoctrine()->getManager();
            $receiver = $em->getRepository('BaseBundle:User')->find($idReceiver);
            $blockForms[$idReceiver] = $this->createReceiverForm($receiver)->createView();
        }


        return $this->render('SignalBundle:usersignal:index.html.twig', array(
            'userSignals' => $pagination,
            'form'=>$formRech->createView(),
            'deleteForm' => $deleteForms,
            'blockForms' => $blockForms,
            'nb'=>$tot , 'nb1'=>$tot1
        ));

    }

    /**
     * Creates a new userSignal entity.
     *
     * @Route("new/{id}", name="Signal_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id)
    {
        $userSignal = new Usersignal();
        $form = $this->createForm('BaseBundle\Form\UserSignalType', $userSignal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //**
            $receiverId = $id;
            $senderId = $this->getUser()->getId();
            $receiver = $em->getRepository('BaseBundle:User')->find($receiverId);

            if (!$receiver) {
                throw $this->createNotFoundException(
                    'No receiver found for id '.$receiverId
                );
            }

            $sender = $em->getRepository('BaseBundle:User')->find($senderId);

            if (!$sender) {
                throw $this->createNotFoundException(
                    'No sender found for id '.$senderId
                );
            }

            $userSignal->setState(false);
            $userSignal->setReceiver($receiver);
            $userSignal->setSender($sender);
            //**

            $em->persist($userSignal);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('infoSignal', 'The user has been reported !');

            //count listReceiver
            $listReceiver = $em->getRepository('BaseBundle:UserSignal')->findByReceiver($receiver);
            $number = count($listReceiver);

            if($number >= 3) {

                $receiver->setEnabled(0);
                $em->flush($receiver);
            }



        }

        return $this->render('SignalBundle:usersignal:new.html.twig', array(
            'userSignal' => $userSignal,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a userSignal entity.
     *
     * @Route("{id}", name="Signal_show")
     * @Method("GET")
     */
    public function showAction(UserSignal $userSignal)
    {
        $deleteForm = $this->createDeleteForm($userSignal);

        $em = $this->getDoctrine()->getManager();
        $userSignal->setState(true);

        $em->persist($userSignal);
        $em->flush();

        return $this->render('SignalBundle:usersignal:show.html.twig', array(
            'userSignal' => $userSignal,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing userSignal entity.
     *
     * @Route("{id}/edit", name="Signal_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserSignal $userSignal)
    {
        $deleteForm = $this->createDeleteForm($userSignal);
        $editForm = $this->createForm('BaseBundle\Form\UserSignalType', $userSignal);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('Signal_edit', array('id' => $userSignal->getId()));
        }

        return $this->render('SignalBundle:usersignal:edit.html.twig', array(
            'userSignal' => $userSignal,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a userSignal entity.
     *
     * @Route("{id}", name="Signal_delete1")
     * @Method("DELETE")
     */
    public function delete1Action(Request $request, UserSignal $userSignal)
    {
        $form = $this->createDeleteForm($userSignal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userSignal);
            $em->flush();
        }

        return $this->redirectToRoute('Signal_index');
    }

    /**
     * Deletes a userSignal entity.
     *
     * @Route("{idSignal}", name="Signal_delete")
     * @Method("POST")
     *
     * @param Request $request
     * @param $idSignal
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $idSignal)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository("BaseBundle:UserSignal");
        $userSignal = $repository->find($idSignal);
        $idReceiver = $userSignal->getReceiver()->getId();

        if (null === $userSignal) {
            throw new NotFoundHttpException("Signal with id ".$idSignal."does not exist.");
        }

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            //delete signal
            $em->remove($userSignal);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', "The signal has been deleted.");

            //check signal number
            $receiver = $em->getRepository('BaseBundle:User')->find($idReceiver);
            $listReceiver = $em->getRepository('BaseBundle:UserSignal')->findByReceiver($receiver);
            $number = count($listReceiver);

            if($number < 3) {
                $receiver->setEnabled(1);
                $em->flush($receiver);
            }
        }
        return $this->redirectToRoute('Signal_index', array(
            'form'   => $form->createView(),
        ));
    }


    /**
     * Block a user entity.
     *
     * @Route("block/{idReceiver}", name="Signal_block")
     * @Method("POST")
     *
     * @param $idReceiver
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function blockAction(Request $request, $idReceiver)
    {
        $form = $this->get('form.factory')->create();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $repository = $em->getRepository("BaseBundle:User");
            $receiver = $repository->find($idReceiver);

            $receiver->setEnabled(0);
            $em->flush();
        }
        return $this->redirectToRoute('Signal_index', array(
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to delete a userSignal entity.
     *
     * @param UserSignal $userSignal The userSignal entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserSignal $userSignal)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('Signal_delete', array('idSignal' => $userSignal->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    private function createReceiverForm(User $user){
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('Signal_block', array('idReceiver' => $user->getId())))
            ->setMethod('POST')
            ->getForm()
            ;
    }

    /**
     *
     *
     * @Route("/index/bannedUsers", name="Bans")
     *
     */
        public function ShowBannedAction(Request $req){

        $em1 = $this->getDoctrine()->getManager();
        $ban = $em1->getRepository('BaseBundle:User')->findBy(['enabled' => '0']);
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                $ban,
                $req->query->getInt('page', 1)/*page number*/,
                3);/*limit per page*/
        return $this->render('SignalBundle:usersignal:bannedUsers.html.twig',  array(
            'ban'=>$pagination));
    }

    /**
     * Block a user entity.
     *
     * @Route("/index/bannedUsers{id}", name="Remove_ban")
     *
     *
     *
     */

    public function RemoveBan ($id){

        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository("BaseBundle:User")->find($id);
        $repository->setEnabled(1);
        $em->persist($repository);
        $em->flush();
        return $this->redirectToRoute('Bans');
    }

    /**
     * Block a user entity.
     *
     * @Route("/index/information", name="inform")
     *
     *
     *
     */

    public function informAction(){
        $em = $this->getDoctrine()->getManager();
        $state = $em->getRepository('BaseBundle:UserSignal')->findBy(['state' => '0']);
        $state1 = $em->getRepository('BaseBundle:UserSignal')->findBy(['state' => '1']);
        $tot= count($state);
        $tot1= count($state1);
        return $this->render('SignalBundle:usersignal:information.html.twig', array(
            'nb'=>$tot , 'nb1'=>$tot1
        ));
    }
}
