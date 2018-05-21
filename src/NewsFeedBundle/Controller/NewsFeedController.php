<?php

namespace NewsFeedBundle\Controller;

use BaseBundle\Entity\Comment;
use BaseBundle\Entity\Enumerations\NotificationType;
use BaseBundle\Entity\Enumerations\PostType;
use BaseBundle\Entity\Enumerations\ReactionType;
use BaseBundle\Entity\Notification;
use BaseBundle\Entity\Photo;
use BaseBundle\Entity\Post;
use BaseBundle\Entity\PostReaction;
use BaseBundle\Entity\User;
use BaseBundle\Repository\CommentRepository;
use BaseBundle\Repository\PostReactionRepository;
use BaseBundle\Repository\PhotoRepository;
use MatchBundle\Service\MatchCardService;
use NewsFeedBundle\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use BaseBundle\Entity\Promotion;
use MongoDB\BSON\Timestamp;
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

use BaseBundle\Form\AdvertType;
use PubliciteBundle\Entity\Advert2;
use PayPal\Api\Transaction;
use BaseBundle\Entity\Advert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use PubliciteBundle\Repository\AdvertRepository;
use Symfony\Component\HttpFoundation\Response;

use Swift_Message;
use Symfony\Component\Validator\Constraints\Date;

class NewsFeedController extends Controller
{
    /**
     * @Route("/", name="news_feed")
     */
    public function newsFeedAction()
    {
        /**@var PhotoRepository $repo */

        $user = $this->getUser();
        $posts = PostService::getPosts($this->getDoctrine(), $user);
        $repo = $this->getDoctrine()->getRepository(Photo::class);
        $photoUrl = $repo->getProfilePhotoUrl($user);
        $admin = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $adminPost = $this->getDoctrine()->getRepository(Post::class)->findOneBy(
            ['user' => $admin],
            ['id' => 'DESC']
        );
        if($adminPost != null) $adminPost->setTime(MatchCardService::getTimeDiffString($adminPost->getDate()));

        return $this->render('NewsFeedBundle:NewsFeed:news_feed.html.twig', array(
            'posts' => $posts,
            'photo' => $photoUrl,
            'online' => $user,
            'StatusType' => PostType::Status,
            'PictureType' => PostType::Picture,
            'adminPost' => $adminPost
        ));
    }

    /**
     * @Route("create_post", name = "create_post")
     * @param Request $request
     * @return JsonResponse
     */
    public function createPostAction(Request $request){
        if($request->isXmlHttpRequest()){
            $text = $request->get("text");
            $user = $this->container->get("security.token_storage")->getToken()->getUser();
            $post = new Post;
            $post->setContent($text);
            $post->setUser($user);
            date_default_timezone_set("Africa/Tunis");
            $post->setDate(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $serializer = new Serializer([new ObjectNormalizer()]);
            $post = PostService::createStatusPost($post, $this->getDoctrine());
            $photoUrl = $this->getDoctrine()->getRepository(Photo::class)->getProfilePhotoUrl($user);
            $content = [];
            $content [] = $this->render('@NewsFeed/NewsFeed/post.html.twig',[
                'post' => $post,
                'online' => $this->getUser(),
                'photo' => $photoUrl,
                'StatusType' => PostType::Status,
                'PictureType' => PostType::Picture
            ])->getContent();

            return new JsonResponse($content);
        }
    }

    /**
     * @Route("edit_post", name = "edit_post")
     * @param Request $request
     * @return JsonResponse
     */
    public function editPostAction(Request $request){
        if($request->isXmlHttpRequest()){
            $text = $request->get('text');
            $id = $request->get('id');

            $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
            $post->setContent($text);

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return new JsonResponse();
        }
    }

    /**
     * @Route("delete_post", name = "delete_post")
     * @param Request $request
     * @return JsonResponse
     */
    public function deletePostAction(Request $request){
        if($request->isXmlHttpRequest()){
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
    }

    /**
     * @Route("react", name = "react")
     * @param Request $request
     * @return JsonResponse
     */
    public function reactAction(Request $request){
        /** @var PostReaction $reaction */
        if($request->isXmlHttpRequest()){
            /* Parse data */
            $user = $this->container->get("security.token_storage")->getToken()->getUser();
            $id = $request->get('id');
            $type = $request->get('type');
            $reactionType = $request->get('reaction');
            $reactionType = isset(ReactionType::getEnumAsArray()[$reactionType]) ? ReactionType::getEnumAsArray()[$reactionType] : -1;
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
                $reaction = $exists[0];

                /* If same reaction or reaction was none */
                if($reaction->getReaction() == $reactionType || $reactionType == -1){
                    $this->deleteReaction($reaction);
                    $data = [
                        'title' => 'None'
                    ];
                }
                /* If different reaction */
                else{
                    $reaction->setReaction($reactionType);
                    $this->updateReaction($reaction);
                    $data = [
                        'title' => ReactionType::getName($reactionType)
                    ];
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

                /* Create Notification */
                $notif = new Notification();
                $notif->setSender($this->getUser());
                $notif->setPostId($postId);
                $notif->setPhotoId($photoId);
                date_default_timezone_set("Africa/Tunis");
                $notif->setDate(new \DateTime());
                $notif->setSeen(false);
                $notif->setType(NotificationType::Reaction);
                $notif->setIcon(null);
                if($postId != 0){
                    $receiver = $this->getDoctrine()->getRepository(Post::class)->find($postId)->getUser();
                    $notif->setContent("has reacted to your post.");
                }
                else{
                    $receiver = $this->getDoctrine()->getRepository(Photo::class)->find($photoId)->getUser();
                    $notif->setContent("has reacted to your photo.");
                }
                $notif->setReceiver($receiver);
                $this->getDoctrine()->getManager()->persist($notif);
                $this->getDoctrine()->getManager()->flush();
            }

            $post = $this->preparePost($photoId, $postId);
            $data ['stats'] = $this->render('@NewsFeed/NewsFeed/postStats.html.twig',[
                'post' => $post
            ])->getContent();

            $serializer = new Serializer([new ObjectNormalizer()]);
            $data = $serializer->normalize($data);

            return new JsonResponse($data);
        }
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
     * @Route("add_comment", name = "add_comment")
     * @param Request $request
     * @return JsonResponse
     */
    public function addCommentAction(Request $request){
        if($request->isXmlHttpRequest()){
            /* Retrieve data */
            $postId = $request->get('postId');
            $photoId = $request->get('photoId');
            $content = $request->get('content');
            $user = $this->getUser();

            /* Create comment */
            $comment = new Comment();
            $comment->setSender($user);
            $comment->setReceiver($user);
            $comment->setPhotoId($photoId);
            $comment->setPostId($postId);
            $comment->setContent($content);
            date_default_timezone_set("Africa/Tunis");
            $comment->setDate(new \DateTime());
            $comment->setProfilePhoto($this->getDoctrine()->getRepository(Photo::class)->getProfilePhotoUrl($user));

            /* Persist comment */
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            /* Create Notification */
            $notif = new Notification();
            $notif->setSender($this->getUser());
            $notif->setPostId($postId);
            $notif->setPhotoId($photoId);
            date_default_timezone_set("Africa/Tunis");
            $notif->setDate(new \DateTime());
            $notif->setSeen(false);
            $notif->setType(NotificationType::Comment);
            $notif->setIcon(null);
            if($postId != 0){
                $receiver = $this->getDoctrine()->getRepository(Post::class)->find($postId)->getUser();
                $notif->setContent("has commented on your post.");
            }
            else{
                $receiver = $this->getDoctrine()->getRepository(Photo::class)->find($photoId)->getUser();
                $notif->setContent("has commented on your photo.");
            }
            $notif->setReceiver($receiver);
            $this->getDoctrine()->getManager()->persist($notif);
            $this->getDoctrine()->getManager()->flush();

            $post = $this->preparePost($photoId, $postId);

            $content = [];
            $content [] = $this->render('@NewsFeed/NewsFeed/comment.html.twig',[
                'online' => $user,
                'comment' => $comment,
                'post' => $post
            ])->getContent();

            $content [] = $this->render('@NewsFeed/NewsFeed/postStats.html.twig',[
                'post' => $post
            ])->getContent();

            $serializer = new Serializer([new ObjectNormalizer()]);
            $content = $serializer->normalize($content);

            return new JsonResponse($content);
        }
    }

    /**
     * @Route("delete_comment", name = "delete_comment")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCommentAction(Request $request){
        if($request->isXmlHttpRequest()){
            $id = $request->get('id');
            $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);

            $photoId = $comment->getPhotoId();
            $postId = $comment->getPostId();

            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();

            $post = $this->preparePost($photoId, $postId);

            $content = [];
            $content [] = $this->render('@NewsFeed/NewsFeed/postStats.html.twig',[
                'post' => $post
            ])->getContent();

            $serializer = new Serializer([new ObjectNormalizer()]);
            $content = $serializer->normalize($content);

            return new JsonResponse($content);
        }
    }

    /**
     * @Route("edit_comment", name = "edit_comment")
     * @param Request $request
     * @return JsonResponse
     */
    public function editCommentAction(Request $request){
        if($request->isXmlHttpRequest()){
            $text = $request->get('text');
            $id = $request->get('id');

            $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);
            $comment->setContent($text);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return new JsonResponse();
        }
    }

    /**
     * @param $photoId int
     * @param $postId int
     * @return Post
     */
    function preparePost($photoId, $postId){
        $post = new Post;
        if($postId == 0){
            $post->setId($photoId);
            $post->setType(PostType::Picture);
            $post->setUser($this->getDoctrine()->getRepository(Photo::class)->find($photoId)->getUser());
        }else{
            $post->setId($postId);
            $post->setType(PostType::Status);
            $post->setUser($this->getDoctrine()->getRepository(Post::class)->find($postId)->getUser());
        }
        $post = PostService::getReactionStats($post, $this->getDoctrine());

        return $post;
    }

    /**
     * @Route("/Increment" , name="increment")
     */

    public function IncrementAction(Request $request)
    {
        $id = $request->request->get('id');
        $advert = new Advert();
        $repo = $this->getDoctrine()->getRepository(Advert::class);
        $advert = $repo->IncrementClickDQL($id);
        $var1 = $this->getDoctrine()->getRepository(Advert::class)->find($id);
        if ($var1->getClicks() == 50) {

            $message = (new Swift_Message())
                ->setSubject('MySoulmate | Add approved !')
                ->setFrom('mysoulmatepi@gmail.com')
                ->setTo($var1->getBusiness()->getEmail())
                ->setBody(
                    "Bonjour Monsieur " . $var1->getBusiness()->getFirstName() . " , Votre Publicité : " . $var1->getContent() . " vient d'achever 
                    le seuil de 50 clicks ! ",

                    'text/html'
                );
            $this->get('mailer')->send($message);
        } else if ($var1->getClicks() == 100) {

            $message = (new Swift_Message())
                ->setSubject('MySoulmate | Add approved !')
                ->setFrom('mysoulmatepi@gmail.com')
                ->setTo($var1->getBusiness()->getEmail())
                ->setBody(
                    "Bonjour Monsieur " . $var1->getBusiness()->getFirstName() . " , Votre Publicité : " . $var1->getContent() . " vient d'achever 
                    le seuil de 100 clicks ! ",

                    'text/html'
                );
            $this->get('mailer')->send($message);
        } else if ($var1->getClicks() == 150) {

            $message = (new Swift_Message())
                ->setSubject('MySoulmate | Add approved !')
                ->setFrom('mysoulmatepi@gmail.com')
                ->setTo($var1->getBusiness()->getEmail())
                ->setBody(
                    "Bonjour Monsieur " . $var1->getBusiness()->getFirstName() . " , Votre Publicité : " . $var1->getContent() . " vient d'achever 
                    le seuil de 150 clicks ! ",

                    'text/html'
                );
            $this->get('mailer')->send($message);
        } else if ($var1->getClicks() == 200) {

            $message = (new Swift_Message())
                ->setSubject('MySoulmate | Add approved !')
                ->setFrom('mysoulmatepi@gmail.com')
                ->setTo($var1->getBusiness()->getEmail())
                ->setBody(
                    "Bonjour Monsieur " . $var1->getBusiness()->getFirstName() . " , Votre Publicité : " . $var1->getContent() . " vient d'achever 
                    le seuil de 250 clicks ! ",

                    'text/html'
                );
            $this->get('mailer')->send($message);
        }
        return new Response("nice done kid");
    }

}
