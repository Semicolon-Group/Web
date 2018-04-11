<?php

namespace MessageBundle\Controller;

use BaseBundle\Entity\Photo;
use BaseBundle\Entity\Thread;
use BaseBundle\Entity\ThreadMetadata;
use BaseBundle\Entity\User;
use MatchBundle\Service\MatchCardService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\MessageBundle\Provider\ProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Tests\SerializerTest;

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
            $thread->time = MatchCardService::getTimeDiffString($StdThread->getLastMessage()->getCreatedAt());
            $threads [] = $thread;
        }

        return $this->container->get('templating')->renderResponse('MessageBundle:Message:layout.html.twig', array(
            'threads' => $threads,
        ));
    }

    /**
     * @param int $userId
     * @return Response
     */
    public function popupAction($userId){
        $type = 'local';
        if($userId == -1){
            if(isset($_SESSION['partId'])){
                $userId = $_SESSION['partId'];
                $type = 'global';
            }
            else{
                return new Response();
            }
        }
        $participant = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $thread = $this->getThread($this->getUser(), $participant);
        /* if thread doesn't exist */
        if($thread == null){
            $StdThread = new Thread();
        }
        /* if thread exists */
        else{
            $StdThread = $this->getProvider()->getThread($thread->getId());
            $em = $this->getDoctrine()->getManager();
            $StdThread->setIsReadByParticipant($this->getUser(), $StdThread->isReadByParticipant($this->getUser()));
            $em->persist($StdThread);
            $em->flush();
        }
        $thread = new \stdClass();
        $participant = $this->getDoctrine()->getRepository(User::class)->find($participant->getId());
        $thread->photo = $this->getDoctrine()->getRepository(Photo::class)->getProfilePhotoUrl($participant);
        $thread->participant = $participant;
        $thread->thread = $StdThread;
        return $this->render('MessageBundle:Message:threadPopup.html.twig', array(
            'thr' => $thread,
            'online' => $this->getUser(),
            'type' => $type,
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
            $participant = $this->getDoctrine()->getRepository(User::class)->find($request->get('partId'));
            $thread = $this->getThread($participant, $this->getUser());
            /* if thread exists */
            if($thread != null){
                $thread = $this->getProvider()->getThread($thread->getId());
                $message = $composer->reply($thread)->setBody($text)->setSender($this->getUser())->getMessage();
            }
            /* if thread doesn't exist */
            else{
                $message = $composer->newThread()->addRecipient($participant)->setSubject('hi')->setSender($this->getUser())->setBody($text)->getMessage();
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
            $participant = $this->getDoctrine()->getRepository(User::class)->find($request->get('partId'));
            $thread = $this->getThread($participant, $this->getUser());
            $_SESSION['partId'] = $participant->getId();
            if($thread != null){
                $thread = $this->getProvider()->getThread($thread->getId());
                $thread->setIsReadByParticipant($this->getUser(), true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($thread);
                $em->flush();
            }
            return new JsonResponse();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function closeThreadAction(Request $request){
        if($request->isXmlHttpRequest()){
            if(isset($_SESSION['partId']) && $_SESSION['partId'] == $request->get('partId'))
                unset($_SESSION['partId']);
            return new JsonResponse();
        }
    }

    /**
     * @return Response
     */
    public function messageIconAction(){
        $threads = $this->getProvider()->getInboxThreads();
        $nbr = 0;
        foreach ($threads as $thread){
            if(!$thread->isReadByParticipant($this->getUser()))
                $nbr++;
        }
        return $this->render('@Message/Message/msg_icon.html.twig',[
            'nbr' => $nbr
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateThreadAction(Request $request){
        if($request->isXmlHttpRequest()){
            $data = [];
            $nbr = $request->get('nbr');
            $participant = $this->getDoctrine()->getRepository(User::class)->find($request->get('partId'));
            $thread = $this->getThread($participant, $this->getUser());
            if($thread != null){
                $thread = $this->getProvider()->getThread($thread->getId());
                $messages = $thread->getMessages();
                $count = count($messages);
                if($count > $nbr){
                    for($i=$nbr; $i < $count; $i++){
                        if($messages[$i]->getSender()->getId() != $this->getUser()->getId())
                            $data [] = $messages[$i]->getBody();
                    }
                }
            }
            $serializer = new Serializer([new ObjectNormalizer()]);
            $data = $serializer->normalize($data);
            return new JsonResponse($data);
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

    /**
     * @param User $part1
     * @param User $part2
     * @return Thread
     */
    public function getThread($part1, $part2){
        return $this->getDoctrine()->getRepository(Thread::class)->getCommonThread($part1, $part2);
        /*$tmd1 = $this->getDoctrine()->getRepository(ThreadMetadata::class)->findBy([
            'participant' => $part1
        ]);
        $tmd2 = $this->getDoctrine()->getRepository(ThreadMetadata::class)->findBy([
            'participant' => $part2
        ]);
        $thread = null;
        $found = false;
        foreach ($tmd1 as $t1){
            foreach ($tmd2 as $t2){
                if($t1->getThread()->getId() == $t2->getThread()->getId()){
                    $thread = $t1->getThread();
                    $found = true;
                    break;
                }
            }
            if($found == true) break;
        }
        return $thread;*/
    }
}
