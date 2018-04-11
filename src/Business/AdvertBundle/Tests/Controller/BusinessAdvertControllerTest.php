<?php

namespace Business\AdvertBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BusinessAdvertControllerTest extends WebTestCase
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

    public function testModifier()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/Modifier');
    }

}
