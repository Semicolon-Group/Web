<?php

namespace MessageBundle\Controller;

use BaseBundle\Entity\Photo;
use BaseBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\MessageBundle\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Displays the authenticated participant inbox.
     *
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function inboxAction()
    {
        $StdThreads = $this->getProvider()->getInboxThreads();
        $threads = [];
        foreach ($StdThreads as $StdThread){
            $thread = new \stdClass();
            $participant = $StdThread->getParticipants()[0]->getId() == $this->getUser()->getId() ? $StdThread->getParticipants()[1] : $StdThread->getParticipants()[0];
            $participant = $this->getDoctrine()->getRepository(User::class)->find($participant->getId());
            $thread->photo = $this->getDoctrine()->getRepository(Photo::class)->getProfilePhotoUrl($participant);
            $thread->participant = $participant;
            $thread->thread = $StdThread;
            $threads [] = $thread;
        }

        return $this->container->get('templating')->renderResponse('MessageBundle:Message:layout.html.twig', array(
            'threads' => $threads,
        ));
    }

    /**
     * @param string $threadId
     * @return Response
     */
    public function popupAction($threadId){
        $StdThread = $this->getProvider()->getThread($threadId);
        $thread = new \stdClass();
        $participant = $StdThread->getParticipants()[0]->getId() == $this->getUser()->getId() ? $StdThread->getParticipants()[1] : $StdThread->getParticipants()[0];
        $participant = $this->getDoctrine()->getRepository(User::class)->find($participant->getId());
        $thread->photo = $this->getDoctrine()->getRepository(Photo::class)->getProfilePhotoUrl($participant);
        $thread->participant = $participant;
        $thread->thread = $StdThread;
        $em = $this->getDoctrine()->getManager();
        $StdThread->setIsReadByParticipant($this->getUser(), $StdThread->isReadByParticipant($this->getUser()));
        $em->persist($StdThread);
        $em->flush();
        return $this->render('MessageBundle:Message:threadPopup.html.twig', array(
            'thr' => $thread,
            'online' => $this->getUser(),
        ));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendAction(Request $request){
        if($request->isXmlHttpRequest()){
            $text = $request->get('text');
            $thread = $this->getProvider()->getThread($request->get('threadId'));
            $composer = $this->container->get('fos_message.composer');
            $message = $composer->reply($thread)->setBody($text)->setSender($this->getUser())->getMessage();
            $sender = $this->container->get('fos_message.sender');
            $sender->send($message);
            $thread->setIsReadByParticipant($this->getUser(), true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($thread);
            $em->flush();
            return new JsonResponse();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function readThreadAction(Request $request){
        if($request->isXmlHttpRequest()){
            $thread = $this->getProvider()->getThread($request->get('threadId'));
            $thread->setIsReadByParticipant($this->getUser(), true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($thread);
            $em->flush();
            return new JsonResponse();
        }
    }

    /**
     * Create a new message thread.
     *
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function newThreadAction()
    {
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('_message_thread_view', array(
                'threadId' => $message->getThread()->getId(),
            )));
        }

        return $this->container->get('templating')->renderResponse('MessageBundle:Message:newThread.html.twig', array(
            'form' => $form->createView(),
            'data' => $form->getData(),
        ));
    }

    /**
     * Searches for messages in the inbox and sentbox.
     *
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function searchAction()
    {
        $query = $this->container->get('fos_message.search_query_factory')->createFromRequest();
        $threads = $this->container->get('fos_message.search_finder')->find($query);

        return $this->container->get('templating')->renderResponse('MessageBundle:Message:search.html.twig', array(
            'query' => $query,
            'threads' => $threads,
        ));
    }

    /**
     * Gets the provider service.
     *
     * @return ProviderInterface
     */
    protected function getProvider()
    {
        return $this->container->get('fos_message.provider');
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
