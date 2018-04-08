<?php

namespace MessageBundle\Controller;

use BaseBundle\Entity\Photo;
use BaseBundle\Entity\Thread;
use BaseBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param int $userId
     * @return Response
     */
    public function popupAction($threadId, $userId){
        /* if thread doesn't exist */
        if($threadId == 0 && $userId > 0){
            $StdThread = new Thread();
            $StdThread->setId(0);
            $participant = $this->getDoctrine()->getRepository(User::class)->find($userId);
        }
        /* if thread exists */
        else{
            $StdThread = $this->getProvider()->getThread($threadId);
            $em = $this->getDoctrine()->getManager();
            $StdThread->setIsReadByParticipant($this->getUser(), $StdThread->isReadByParticipant($this->getUser()));
            $em->persist($StdThread);
            $em->flush();
            $participant = $StdThread->getParticipants()[0]->getId() == $this->getUser()->getId() ? $StdThread->getParticipants()[1] : $StdThread->getParticipants()[0];
        }
        $thread = new \stdClass();
        $participant = $this->getDoctrine()->getRepository(User::class)->find($participant->getId());
        $thread->photo = $this->getDoctrine()->getRepository(Photo::class)->getProfilePhotoUrl($participant);
        $thread->participant = $participant;
        $thread->thread = $StdThread;
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
            $composer = $this->container->get('fos_message.composer');
            $text = $request->get('text');
            $threadId = $request->get('threadId');
            $userId = $request->get('userId');
            /* if thread exists */
            if($threadId > 0){
                $thread = $this->getProvider()->getThread($threadId);
                $message = $composer->reply($thread)->setBody($text)->setSender($this->getUser())->getMessage();
            }
            /* if thread hasn't been started */
            else{
                $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
                $message = $composer->newThread()->addRecipient($user)->setSubject('hi')->setSender($this->getUser())->setBody($text)->getMessage();
            }
            $sender = $this->container->get('fos_message.sender');
            $sender->send($message);
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
