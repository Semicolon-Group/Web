<?php

namespace MatchBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MatchControllerTest extends WebTestCase
{
    public function testMatches()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/matches');
    }

}
