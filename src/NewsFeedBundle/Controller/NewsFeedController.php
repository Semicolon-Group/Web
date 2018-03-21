<?php

namespace NewsFeedBundle\Controller;

use BaseBundle\Entity\Photo;
use BaseBundle\Entity\Post;
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

        $user = $this->container->get("security.token_storage")->getToken()->getUser();
        $posts = PostService::getPosts($this->getDoctrine(), $user);
        $repo = $this->getDoctrine()->getRepository(Photo::class);
        $photoUrl = $repo->getProfilePhotoUrl($user);
        return $this->render('NewsFeedBundle:NewsFeed:news_feed.html.twig', array(
            'posts' => $posts,
            'photo' => $photoUrl,
            'online' => $user
        ));
    }
    /**
     * @Route("create_post", name = "create_post")
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

            return new JsonResponse();
        }
    }

    /**
     * @Route("edit_post", name = "edit_post")
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
     */
    public function deletePostAction(Request $request){
        if($request->isXmlHttpRequest()){
            $id = $request->get('id');
            $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
            return new JsonResponse();
        }
    }
}
