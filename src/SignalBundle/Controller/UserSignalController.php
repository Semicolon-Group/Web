<?php

namespace SignalBundle\Controller;

use BaseBundle\Entity\UserSignal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $userSignals = $em->getRepository('BaseBundle:UserSignal')->findAll();
        return $this->render('SignalBundle:usersignal:index.html.twig', array(
            'userSignals' => $userSignals,
        ));
    }

    /**
     * Creates a new userSignal entity.
     *
     * @Route("new", name="Signal_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $userSignal = new Usersignal();
        $form = $this->createForm('BaseBundle\Form\UserSignalType', $userSignal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //**
            $receiverId = 1;
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
            return $this->redirectToRoute('Signal_index', array('id' => $userSignal->getId()));
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
     * @Route("{id}", name="Signal_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UserSignal $userSignal)
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
     * Creates a form to delete a userSignal entity.
     *
     * @param UserSignal $userSignal The userSignal entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserSignal $userSignal)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('Signal_delete', array('id' => $userSignal->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
