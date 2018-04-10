<?php
/**
 * Created by PhpStorm.
 * User: Elyes
 * Date: 21/03/2018
 * Time: 15:11
 */

namespace NewsFeedBundle\Service;


use BaseBundle\Entity\Comment;
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
use MatchBundle\Service\MatchCardService;

class PostService
{

    public static function getPosts($doctrine, $user){
        /**
         * @param array $reactions
         * @param array $comments
         * @return object
         */
        function getStats($reactions, $comments){
            $mapped = array_map(function($a){
                /** @var PostReaction $a */
                return ReactionType::getName($a->getReaction());
            }, $reactions);
            $count = count($mapped);
            $mapped = array_unique($mapped);
            $stat = new \stdClass();
            $stat->reactions = $mapped;
            $stat->nbrReaction = $count;
            $stat->nbrComment = count($comments);
            return $stat;
        }

        /**
         * @param Post $post
         * @param ManagerRegistry $doctrine
         * @return array
         */
        function getComments($post, $doctrine){
            $postId = $post->getId();
            $photoId = 0;
            if ($post->getType() == PostType::Picture){
                $postId = 0;
                $photoId = $post->getId();
            }
            $comments = $doctrine->getRepository(Comment::class)->findBy([
                'postId' => $postId,
                'photoId' => $photoId
            ]);
            /** @var PhotoRepository $photoRepo */
            $photoRepo = $doctrine->getRepository(Photo::class);
            foreach ($comments as $comment){
                $comment->setProfilePhoto($photoRepo->getProfilePhotoUrl($comment->getSender()));
            }
            return $comments;
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
            $post->setTime(MatchCardService::getTimeDiffString($post->getDate()));
            $post->setPhotoUrl($photoRepo->getProfilePhotoUrl($post->getUser()));
            $post->setType(PostType::Status);
            $post->setReactions($reactRepo->findBy(['postId' => $post->getId()]));
            $post->setComments(getComments($post, $doctrine));
            $post->setStats(getStats($post->getReactions(), $post->getComments()));
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
            $post->setTime(MatchCardService::getTimeDiffString($photo->getDate()));
            $post->setPhotoUrl($photoRepo->getProfilePhotoUrl($photo->getUser()));
            $post->setDate($photo->getDate());
            $post->setContent('/mysoulmate/web/uploads/images/' . $photo->getImage());
            $post->setReactions($reactRepo->findBy(['photoId' => $photo->getId()]));
            $post->setComments(getComments($post, $doctrine));
            $post->setStats(getStats($post->getReactions(), $post->getComments()));
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