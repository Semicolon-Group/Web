<?php

namespace NewsFeedBundle\Controller;

use BaseBundle\Entity\Comment;
use BaseBundle\Entity\Enumerations\PostType;
use BaseBundle\Entity\Enumerations\ReactionType;
use BaseBundle\Entity\Photo;
use BaseBundle\Entity\Post;
use BaseBundle\Entity\PostReaction;
use BaseBundle\Repository\CommentRepository;
use BaseBundle\Repository\PostReactionRepository;
use BaseBundle\Repository\PhotoRepository;
use NewsFeedBundle\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

        return $this->render('NewsFeedBundle:NewsFeed:news_feed.html.twig', array(
            'posts' => $posts,
            'photo' => $photoUrl,
            'online' => $user,
            'StatusType' => PostType::Status,
            'PictureType' => PostType::Picture
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
            $data = [];
            $user = $this->container->get("security.token_storage")->getToken()->getUser();
            $id = $request->get('id');
            $type = $request->get('type');
            $reactionType = $request->get('reaction');
            $reactionType = isset(ReactionType::getEnumAsArray()[$reactionType]) ? ReactionType::getEnumAsArray()[$reactionType] : -1;
            $postId = 0;
            $photoId = 0;
            if($type == PostType::Status)
                $postId = $id;
            else if($type == PostType::Picture)
                $photoId = $id;

            /* Check if reaction exists */
            $exists = $this->getDoctrine()->getRepository(PostReaction::class)->findBy([
                'postId' => $postId,
                'user' => $user
            ]);
            $exists = array_merge($exists, $this->getDoctrine()->getRepository(PostReaction::class)->findBy([
                'photoId' => $photoId,
                'user' => $user
            ]));

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
            /* If reaction exists and new one was none */
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
            $comment->setDate(new \DateTime());
            $comment->setProfilePhoto($this->getDoctrine()->getRepository(Photo::class)->getProfilePhotoUrl($user));

            /* Persist comment */
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $post = new Post;
            $post->setId($postId == 0 ? $photoId : $postId);

            $content = [];
            $content [] = $this->render('@NewsFeed/NewsFeed/comment.html.twig',[
                'online' => $user,
                'comment' => $comment,
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

            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();

            return new JsonResponse();
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
}
