<?php

namespace Member\RecommandationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecommandationControllerTest extends WebTestCase
{
    public function testRecommandations()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/recommandations');
    }

}
