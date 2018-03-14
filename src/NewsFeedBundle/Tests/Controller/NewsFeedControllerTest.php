<?php

namespace NewsFeedBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NewsFeedControllerTest extends WebTestCase
{
    public function testNewsfeed()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/newsFeed');
    }

}
