<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NivelesRestControllerControllerTest extends WebTestCase
{
    public function testGetniveles()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/get_niveles');
    }

}
