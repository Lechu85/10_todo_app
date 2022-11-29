<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {


	    // create 20 products! Bam!
	    for ($i = 0; $i < 20; $i++) {
		    $product = new User();
		    $product->setName('product '.$i);
		    $product->setDescription('Przykładowy opis ');
		    $product->setPrice(mt_rand(10, 100));
		    $manager->persist($product);
	    }

	    $manager->flush();

        $manager->flush();
    }
}
