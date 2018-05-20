<?php

namespace ServiceBundle\Controller;

use BaseBundle\Entity\Comment;
use BaseBundle\Entity\Enumerations\PostType;
use BaseBundle\Entity\Enumerations\ReactionType;
use BaseBundle\Entity\Message;
use BaseBundle\Entity\Photo;
use BaseBundle\Entity\Post;
use BaseBundle\Entity\PostReaction;
use BaseBundle\Entity\Thread;
use BaseBundle\Entity\User;
use BaseBundle\Repository\CommentRepository;
use BaseBundle\Repository\PostReactionRepository;
use FOS\MessageBundle\Provider\ProviderInterface;
use MatchBundle\Entity\MatchCard;
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
     * @Route("/create_comment", name="create_comment")
     */
    public function createCommentAction(Request $request){
        $comment = new Comment();
        $comment->setSender($this->getDoctrine()->getRepository(User::class)->find($request->get('senderId')));
        $comment->setContent($request->get('text'));
        $comment->setDate(new \DateTime());
        $id = $request->get('postId');
        $type = $request->get('type');
        $postId = $id;
        $photoId = 0;
        if($type == PostType::Picture){
            $postId = 0;
            $photoId = $id;
        }
        $comment->setPhotoId($photoId);
        $comment->setPostId($postId);
        $this->getDoctrine()->getManager()->persist($comment);
        $this->getDoctrine()->getManager()->flush();
        $data = [];
        $c = [
            'id' => $comment->getId(),
            'senderId' => $comment->getSender()->getId(),
            'photoUrl' => $this->getDoctrine()->getRepository(Photo::class)->getProfilePhotoUrl($comment->getSender()),
            'content' => $comment->getContent()
        ];
        $data[] = $c;
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($data);
        return new JsonResponse($data);
    }

    /**
     * @Route("/create_post", name="create_post_service")
     */
    public function createPostAction(Request $request){
        $post = new Post();
        $post->setUser($this->getDoctrine()->getRepository(User::class)->find($request->get('userId')));
        $post->setContent($request->get('text'));
        $post->setDate(new \DateTime());
        $this->getDoctrine()->getManager()->persist($post);
        $this->getDoctrine()->getManager()->flush();
        $data = [];
        $p = [
            'id' => $post->getId(),
            'type' => PostType::Status,
            'userId' => $post->getUser()->getId(),
            'userPhoto' => $this->getDoctrine()->getRepository(Photo::class)->getProfilePhotoUrl($post->getUser()),
            'userName' => $post->getUser()->getFirstName() . ' ' . $post->getUser()->getLastName(),
            'time' => "Now",
            'content' => $post->getContent(),
            'currReaction' => 0,
            'nbrReaction' => 0,
            'nbrComment' => 0
        ];
        $data[] = $p;
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($data);
        return new JsonResponse($data);
    }

    /**
     * @Route("/delete_comment", name="delete_comment_service")
     */
    public function deleteCommentAction(Request $request){
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($request->get('id'));
        $this->getDoctrine()->getManager()->remove($comment);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse();
    }

    /**
     * @Route("/delete_post", name="delete_post_service")
     */
    public function deletePostAction(Request $request){
        /** @var PostReactionRepository $reactRepo */
        /** @var CommentRepository $commentRepo */
        /** @var Post $post */

        $id = $request->get('id');
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        /* delete reactions of this post */
        $reactRepo = $this->getDoctrine()->getRepository(PostReaction::class);
        $reactRepo->deleteByPost($id);

        /* delete comments of this post */
        $commentRepo = $this->getDoctrine()->getRepository(Comment::class);
        $commentRepo->deleteByPost($id);

        /* delete post */
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * @Route("/react", name="react_service")
     */
    public function reactAction(Request $request){
        /* Parse data */
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('userId'));
        $id = $request->get('id');
        $type = $request->get('type');
        $reactionType = $request->get('reaction');
        $postId = 0;
        $photoId = 0;

        if($type == PostType::Status){
            $postId = $id;
            $exists = $this->getDoctrine()->getRepository(PostReaction::class)->findByPost($postId, $user);
        }
        else if($type == PostType::Picture){
            $photoId = $id;
            $exists = $this->getDoctrine()->getRepository(PostReaction::class)->findByPhoto($photoId, $user);
        }

        /* If reaction exists */
        if(!empty($exists)){
            /** @var PostReaction $reaction */
            $reaction = $exists[0];

            /* If same reaction or reaction was none */
            if($reaction->getReaction() == $reactionType || $reactionType == -1){
                $this->deleteReaction($reaction);
            }
            /* If different reaction */
            else{
                $reaction->setReaction($reactionType);
                $this->updateReaction($reaction);
            }
        }
        /* If reaction doesn't exist and new one was none */
        else if($reactionType == -1){
            $data = [
                'title' => 'None'
            ];
        }
        /* If reaction doesn't exist and new one wasn't none */
        else{
            $data = [
                'title' => ReactionType::getName($reactionType)
            ];
            $reaction = new PostReaction();
            $reaction->setUser($user);
            $reaction->setExperienceId(0);
            $reaction->setReaction($reactionType);
            $reaction->setPostId($postId);
            $reaction->setPhotoId($photoId);

            $this->updateReaction($reaction);
        }

        return new JsonResponse();
    }

    function deleteReaction($reaction){
        $em = $this->getDoctrine()->getManager();
        $em->remove($reaction);
        $em->flush();
    }

    function updateReaction($reaction){
        $em = $this->getDoctrine()->getManager();
        $em->persist($reaction);
        $em->flush();
    }

    /**
     * @Route("/get_cards", name="get_cards_service")
     */
    public function getCardsAction(Request $request){
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
        $cards = MatchCardService::getMatches($this->getDoctrine(), $user, null, "service");
        $data = [];
        foreach ($cards as $card){
            /** @var MatchCard $card */
            $c = [
                'memberId' => $card->getUser()->getId(),
                'match' => $card->getMatch(),
                'enemy' => $card->getEnemy(),
                'name' => $card->getUser()->getFirstname() . ' ' . $card->getUser()->getLastname(),
                'photoUrl' => $card->getPhoto()
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
                'body' => $message->getBody(),
                'id' =>$message->getId()

            ];
            $data[] = $c;
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($data);
        return new JsonResponse($data);
    }
    /**
     * @Route("/get_messages2", name="get_messages99")
     */
    public function getMessagesAction2(Request $request){
        $sender = $this->getDoctrine()->getRepository(User::class)->find($request->get('sender'));
        $receiver = $this->getDoctrine()->getRepository(User::class)->find($request->get('receiver'));
        $thread = $this->getThread($sender, $receiver);
        $data = [];

            /** @var Message $message */
            $c = [
                'senderId' => $sender->getId(),
                'receiver'=>$receiver->getId(),


                'thread'=>$thread->getId()

            ];
            $data[] = $c;

        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($data);
        return new JsonResponse($data);
    }

    /**
     * @Route("/send_message", name="send_message")
     */
    //
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

    /**
     * @Route("get_sff", name="updates")
     */
    public function update(Request $request){

            $data = [];
            $nbr = $request->get('nbr');
            $participant = $this->getDoctrine()->getRepository(User::class)->find($request->get('Id'));
            $receiver = $this->getDoctrine()->getRepository(User::class)->find($request->get('receiver'));

        $thread = $this->getThread($receiver,$participant);
            if($thread != null){



                $messages = $thread->getMessages();


                $count = count($messages);
                if($count > $nbr){
                    for($i=$nbr; $i < $count; $i++){
                        if($messages[$i]->getSender()->getId() != $participant->getId())
                        {
                            $c = [
                                'senderId' => $messages[$i]->getSender()->getId(),
                                'body' => $messages[$i]->getBody(),
                                'id'=>$messages[$i]->getId()
                            ];
                        $data[] = $c;
                        }
                    }
                }
            }
            $serializer = new Serializer([new ObjectNormalizer()]);
            $data = $serializer->normalize($data);
            return new JsonResponse($data);

    }



}
