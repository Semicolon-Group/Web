<?php

namespace PubliciteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testLister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Lister');
    }

    public function testTraiter()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Traiter');
    }

}
