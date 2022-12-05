<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient(); //tworzymy klienta
        $crawler = $client->request('GET', '/'); //który wykonuje polecenie get
	    //w dokumentacji jest opis metody request, można robić to z większa precyzją.

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Witamy w aplikacji treningowej ToDo App :)'); //na stronie głównej ma być hello world w h1
        //Czyli strona ma mieć H1 z taką zawartością

	}
}
