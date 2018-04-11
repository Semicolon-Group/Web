<?php

namespace PubliciteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdvertControllerTest extends WebTestCase
{
    public function testAfficher()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Afficher');
    }

    public function testAjouter()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Ajouter');
    }

    public function testLister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Lister');
    }

}
