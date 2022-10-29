<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(EntityManagerInterface $entityManager): Response
    {

		$product = new Product();

		$category = new Category();
		$category->setName('Nowaa Kategoria');

		$product->setName('Nowy produkt')
				->setDescription('Opis naszego produktu')
				->setPrice(123.99)
				->setActive(true)
				->setEan('888989898989')
				->setCategory($category);

	    $entityManager->persist($category);
		$entityManager->persist($product);
		$entityManager->flush();

		return new Response('Product saved');

    }

	#[Route('/products', name: 'products_list')]
	public function products(EntityManagerInterface $entityManager)
	{
		$products = $entityManager->getRepository(Product::class)->findAll();
		dd($products);

	}

	#[Route('/product/{id}', name: 'product_show')]
	public function show(EntityManagerInterface $entityManager, int $id)
	{
		$product = $entityManager->getRepository(Product::class)->find($id);
		dd($product->getCategory()->getName());//wtedy pobiera rowniez z tabeli dolaczonej category
		//dd($product);	//lazy load

	}

	#[Route('/product/edit/{id}', name: 'product_edit')]
	public function update(EntityManagerInterface $entityManager, int $id): Response
	{
		$product = $entityManager->getRepository(Product::class)->find($id);

		if (!$product) {
			throw $this->createNotFoundException(
				'Nie znaleziono produktu o id '.$id
			);
		}

		$product->setPrice(99.99);
		$entityManager->flush();

		return $this->redirectToRoute('product_show', [
			'id' => $product->getId()
		]);
	}

	#[Route('/product/remove/{id}', name: 'product_remove')]
	public function remove(EntityManagerInterface $entityManager, int $id): Response
	{
		$product = $entityManager->getRepository(Product::class)->find($id);

		if (!$product) {
			throw $this->createNotFoundException(
				'Nie znaleziono produktu o id '.$id
			);
		}

		$entityManager->remove($product);
		$entityManager->flush();

		return $this->redirectToRoute('products_list', [
			'id' => $product->getId()
		]);
	}

	#[Route('/sales', name: 'product_sales')]
	public function sales(EntityManagerInterface $entityManager): Response
	{
		//$products = $entityManager->getRepository(Product::class)->findAllLoverThanPrice(100);
		$products = $entityManager->getRepository(Product::class)->findAllLoverThanPrice(100);

		dd($products);
	}


	#[Route('/top-prod', name: 'product_top_prod')]
	public function top_prod(EntityManagerInterface $entityManager): Response
	{
		//$products = $entityManager->getRepository(Product::class)->findAllLoverThanPrice(100);
		$products = $entityManager->getRepository(Product::class)->findAllGreaterThanPriceDql(200, false);

		dd($products);
	}
}
