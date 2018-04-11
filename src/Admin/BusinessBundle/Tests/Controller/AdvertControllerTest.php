<?php

namespace Admin\BusinessBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdvertControllerTest extends WebTestCase
{
    public function testModifier()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Modifier');
    }

    public function testLister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Lister');
    }

}
