<?php

namespace App\Tests\Repository;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest //extends KernelTestCase
{/*
	public function testAddProduct()
	{

		// (1) boot the Symfony kernel
		self::bootKernel();
		// (2) use static::getContainer() to access the service container
		$container = static::getContainer();
		//te dwie linijki muszą być, jeżeli potrzebujemy np EntityManagera

		$entityManager = $container->get(EntityManagerInterface::class);


		$product = new Product();
		$category = new Category();
		$category->setName('Nowa Kategoria');

		$product->setName('Koszulka')
			->setDescription('Opis tego fajnego produktu')
			->setPrice( 99.99)
			->setActive(true)
			->setEan('895533857235563')
			->setCategory($category);

		$entityManager->persist($category);
		$entityManager->persist($product);
		$entityManager->flush();

		//tutaj jż siędodaje
		//teraz żeby sprawdzić, potrzebujemy repozytorium z produktu

		$repository = $entityManager->getRepository(Product::class);
		$product = $repository->findOneBy(['name' => 'Koszulka']);

		$this->assertInstanceOf(Product::class, $product);//sprawdzamy czy jest instancją klasy produkt
		$this->assertIsInt($product->getId());//czy id jest intigerem

	}*/
}
