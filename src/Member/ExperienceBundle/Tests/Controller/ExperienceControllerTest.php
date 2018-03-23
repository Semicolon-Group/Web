<?php

namespace Member\ExperienceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExperienceControllerTest extends WebTestCase
{
    public function testExperiences()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/experiences');
    }

}
