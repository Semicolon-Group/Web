<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 21/03/2018
 * Time: 15:11
 */

namespace NewsFeedBundle\Service;


use BaseBundle\Entity\Enumerations\PostType;
use BaseBundle\Entity\Enumerations\ReactionType;
use BaseBundle\Entity\Photo;
use BaseBundle\Entity\Post;
use BaseBundle\Entity\PostReaction;
use BaseBundle\Entity\User;
use BaseBundle\Repository\PhotoRepository;
use BaseBundle\Repository\PostReactionRepository;
use BaseBundle\Repository\PostRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PostService
{

    public static function getPosts($doctrine, $user){
        /**
         * @param array $reactions
         * @return object
         */
        function getStats($reactions){
            $mapped = array_map(function($a){
                /** @var PostReaction $a */
                return ReactionType::getName($a->getReaction());
            }, $reactions);
            $count = count($mapped);
            $mapped = array_unique($mapped);
            $stat = new \stdClass();
            $stat->reactions = $mapped;
            $stat->nbr = $count;
            return $stat;
        }

        /** @var ManagerRegistry $doctrine */
        /** @var User $user */
        /** @var PhotoRepository $photoRepo */
        /** @var PostRepository $postRepo */
        /** @var PostReactionRepository $reactRepo */
        /** @var Post $post */
        /** @var Photo $photo */

        $postRepo = $doctrine->getRepository(Post::class);
        $reactRepo = $doctrine->getRepository(PostReaction::class);
        $photoRepo = $doctrine->getRepository(Photo::class);

        $posts = $postRepo->getPosts($user);
        $posts = array_merge($posts, $postRepo->findBy(['user' => $user]));
        foreach($posts as $post){
            $post->setPhotoUrl($photoRepo->getProfilePhotoUrl($post->getUser()));
            $post->setType(PostType::Status);
            $post->setReactions($reactRepo->findBy(['postId' => $post->getId()]));
            $post->setStats(getStats($post->getReactions()));
            $currentReaction = $reactRepo->findBy(['postId' => $post->getId(), 'user' => $user]);
            if(empty($currentReaction)){
                $currentReaction = -1;
                $post->getStats()->currReacTitle = 'None';
            }
            else {
                $currentReaction = $currentReaction[0]->getReaction();
                $post->getStats()->currReacTitle = ReactionType::getName($currentReaction);
            }
            $post->setCurrentReaction($currentReaction);
        }

        $photos = $photoRepo->getPostPics($user);
        $photos = array_merge($photos, $photoRepo->findBy(['user' => $user]));
        foreach ($photos as $photo){
            $post = new Post;
            $post->setId($photo->getId());
            $post->setUser($photo->getUser());
            $post->setType(PostType::Picture);
            $post->setPhotoUrl($photoRepo->getProfilePhotoUrl($photo->getUser()));
            $post->setDate($photo->getDate());
            $post->setContent('/mysoulmateuploads/images/' . $photo->getUrl());
            $post->setReactions($reactRepo->findBy(['photoId' => $photo->getId()]));
            $post->setStats(getStats($post->getReactions()));
            $currentReaction = $reactRepo->findBy(['photoId' => $photo->getId(), 'user' => $user]);
            if(empty($currentReaction)){
                $currentReaction = -1;
                $post->getStats()->currReacTitle = 'None';
            }
            else {
                $currentReaction = $currentReaction[0]->getReaction();
                $post->getStats()->currReacTitle = ReactionType::getName($currentReaction);
            }
            $post->setCurrentReaction($currentReaction);
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