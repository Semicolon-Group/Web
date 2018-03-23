<?php

namespace Member\NewsFeedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class NewsFeedController extends Controller
{
    /**
     * @Route("/", name="news_feed")
     */
    public function newsFeedAction()
    {
        return $this->render('MemberNewsFeedBundle:NewsFeed:news_feed.html.twig', array(
            // ...
        ));
    }

}
