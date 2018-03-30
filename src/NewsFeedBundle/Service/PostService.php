<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 21/03/2018
 * Time: 15:11
 */

namespace NewsFeedBundle\Service;


use BaseBundle\Entity\Photo;
use BaseBundle\Entity\Post;
use BaseBundle\Entity\User;
use BaseBundle\Repository\PhotoRepository;
use BaseBundle\Repository\PostRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PostService
{
    public static function getPosts($doctrine, $user){
        /** @var ManagerRegistry $doctrine */
        /** @var User $user */
        /** @var PhotoRepository $photoRepo */
        /** @var PostRepository $postRepo */
        /** @var Post $post */
        /** @var Photo $photo */

        $postRepo = $doctrine->getRepository(Post::class);
        $photoRepo = $doctrine->getRepository(Photo::class);

        $posts = $postRepo->getPosts($user);
        $posts = array_merge($posts, $postRepo->findBy(['user' => $user]));
        foreach($posts as $post){
            $post->setPhotoUrl($photoRepo->getProfilePhotoUrl($post->getUser()));
            $post->setType(false);
        }

        $photos = $photoRepo->getPostPics($user);
        $photos = array_merge($photos, $photoRepo->findBy(['user' => $user]));
        foreach ($photos as $photo){
            $post = new Post;
            $post->setUser($photo->getUser());
            $post->setType(true);
            $post->setPhotoUrl($photoRepo->getProfilePhotoUrl($photo->getUser()));
            $post->setDate($photo->getDate());
            $post->setContent($photo->getUrl());
            $posts[] = $post;
        }

        usort($posts, function($a,$b){
            /** @var Post $a */
            /** @var Post $b */
            return $a->getDate() < $b->getDate();
        });

        return $posts;
    }
}