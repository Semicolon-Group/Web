<?php

namespace ServiceBundle\Controller;

use BaseBundle\Entity\Comment;
use BaseBundle\Entity\Message;
use BaseBundle\Entity\Post;
use BaseBundle\Entity\Thread;
use BaseBundle\Entity\User;
use FOS\MessageBundle\Provider\ProviderInterface;
use MatchBundle\Service\MatchCardService;
use NewsFeedBundle\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ElyesController extends Controller  implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Route("/get_posts", name="get_posts")
     */
    public function getPostsAction(Request $request){
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
        $posts = PostService::getPosts($this->getDoctrine(), $user);
        $data = [];
        foreach ($posts as $post){
            /** @var Post $post */
            $p = [
                'id' => $post->getId(),
                'type' => $post->getType(),
                'userId' => $post->getUser()->getId(),
                'userPhoto' => $post->getPhotoUrl(),
                'userName' => $post->getUser()->getFirstName() . ' ' . $post->getUser()->getLastName(),
                'time' => $post->getTime(),
                'content' => $post->getContent(),
                'currReaction' => $post->getCurrentReaction(),
                'nbrReaction' => $post->getStats()->nbrReaction,
                'nbrComment' => $post->getStats()->nbrComment
            ];
            $data[] = $p;
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($data);
        return new JsonResponse($data);
    }

    /**
     * @Route("/get_comments", name="get_comments")
     */
    public function getCommentsAction(Request $request){
        $post = new Post;
        $post->setId($request->get('id'));
        $post->setType($request->get('type'));
        $comments = PostService::getComments($post, $this->getDoctrine());
        $data = [];
        foreach ($comments as $comment){
            /** @var Comment $comment */
            $c = [
                'id' => $comment->getId(),
                'senderId' => $comment->getSender()->getId(),
                'photoUrl' => $comment->getProfilePhoto(),
                'content' => $comment->getContent()
            ];
            $data[] = $c;
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($data);
        return new JsonResponse($data);
    }

    /**
     * @Route("/get_threads", name="get_threads")
     */
    public function getThreadsAction(Request $request){
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
        $threads = $this->getDoctrine()->getRepository(Thread::class)->getInboxThreads($user);
        $data = [];
        foreach ($threads as $thread){
            /** @var Thread $thread */
            /** @var User $participant */
            $participant = $thread->getOtherParticipants($user)[0];
            $c = [
                'threadId' => $thread->getId(),
                'participantId' => $participant->getId(),
                'lastMessage' => $thread->getLastMessage()->getBody(),
                'timeSince' => MatchCardService::getTimeDiffString($thread->getLastMessage()->getCreatedAt()),
                'participantName' => $participant->getFirstName() . ' ' . $participant->getLastname()
            ];
            $data[] = $c;
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($data);
        return new JsonResponse($data);
    }

    /**
     * @param User $part1
     * @param User $part2
     * @return Thread
     */
    public function getThread($part1, $part2){
        return $this->getDoctrine()->getRepository(Thread::class)->getCommonThread($part1, $part2);
    }

    /**
     * @Route("/get_messages", name="get_messages")
     */
    public function getMessagesAction(Request $request){
        $messages = $this->getDoctrine()->getRepository(Thread::class)->find($request->get('id'))->getMessages();
        $data = [];
        foreach ($messages as $message){
            /** @var Message $message */
            $c = [
                'senderId' => $message->getSender()->getId(),
                'body' => $message->getBody()
            ];
            $data[] = $c;
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($data);
        return new JsonResponse($data);
    }

    /**
     * @Route("/send_message", name="send_message")
     */
    public function sendMessageAction(Request $request){
        $sender = $this->getDoctrine()->getRepository(User::class)->find($request->get('sender'));
        $receiver = $this->getDoctrine()->getRepository(User::class)->find($request->get('receiver'));
        $body = $request->get('body');
        $thread = $this->getThread($sender, $receiver);
        $composer = $this->container->get('fos_message.composer');
        $message = $composer->reply($thread)->setSender($sender)->setBody($body)->getMessage();
        $sender = $this->container->get('fos_message.sender');
        $sender->send($message);
        return new JsonResponse();
    }

    /**
     * @Route("/new_thread", name="new_thread")
     */
    public function newThreadAction(Request $request){
        $sender = $this->getDoctrine()->getRepository(User::class)->find($request->get('sender'));
        $receiver = $this->getDoctrine()->getRepository(User::class)->find($request->get('receiver'));
        $body = $request->get('body');
        $composer = $this->container->get('fos_message.composer');
        $message = $composer->newThread()->addRecipient($receiver)->setSubject('hi')->setSender($sender)->setBody($body)->getMessage();
        $sender = $this->container->get('fos_message.sender');
        $sender->send($message);
        return new JsonResponse();
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
