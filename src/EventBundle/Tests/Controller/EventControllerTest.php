<?php

namespace EventBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventControllerTest extends WebTestCase
{
    public function testEvents()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/events');
    }

}
