<?php

namespace ServiceBundle\Controller;

use BaseBundle\Entity\Post;
use BaseBundle\Entity\User;
use NewsFeedBundle\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ElyesController extends Controller
{
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
}
