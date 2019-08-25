<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PersonControllerTest extends WebTestCase
{
    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/new');
    }

    public function testModify()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{id}/modify');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{id}/delete');
    }

    public function testDisplayonebyid()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{id}');
    }

    public function testDisplayall()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

}
