<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

	#[Route('/products', name: 'app_products_list')]
	public function products(EntityManagerInterface $entityManager)
	{
		$products = $entityManager->getRepository(Product::class)->findAll();

		return $this->render('product/list.html.twig', [
			'products' => $products,
			'pageTitle' => 'Lista produktów',
		]);

	}


	#[Route('/product/edit/{id}', name: 'app_product_edit')]
	public function update(Request $request, EntityManagerInterface $entityManager, int $id): Response
	{

		$product = $entityManager->getRepository(Product::class)->find($id);

		if (!$product) {
			throw $this->createNotFoundException(
				'Nie znaleziono produktu o id '.$id
			);
		}

		$form = $this->createForm(ProductType::class, $product);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values

			$entityManager->persist($product);
			$entityManager->flush();

			$this->addFlash('success', 'Produkt został edytowany');

			return $this->redirectToRoute('app_product_show', [
				'id' => $product->getId()
			]);
		}

		return $this->renderForm('product/form.html.twig', [
			'form' => $form,
			'pageTitle' => 'Edytuj produkt'
		]);


	}

	#[Route('/product/new', name: 'app_product_new')]
	public function index(Request $request, EntityManagerInterface $entityManager): Response
	{

		$form = $this->createForm(ProductType::class);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values

			$data = $form->getData();


			//dump($data->getDescription());

			//dump($data);

			$product = new Product();
			$category = new Category();
			$category->setName('Nowa Kategoria');

			$product->setName($data->getName())
				->setDescription($data->getDescription())
				->setPrice($data->getPrice())
				->setActive($data->isActive())
				->setEan($data->getEan())
				->setCategory($category);

			$entityManager->persist($category);
			$entityManager->persist($product);
			$entityManager->flush();

			$this->addFlash('success', 'Produkt został dodany');

			return $this->redirectToRoute('app_product_show', [
				'id' => $product->getId()
			]);
		}

		return $this->renderForm('product/form.html.twig', [
			'form' => $form,
			'pageTitle' => 'Dodaj produkt'
		]);

	}

	#[Route('/product/remove/{id}', name: 'app_product_remove')]
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

		return $this->redirectToRoute('app_products_list', [
			'id' => $product->getId()
		]);
	}

	#[Route('/product/{id}', name: 'app_product_show')]
	public function show(EntityManagerInterface $entityManager, int $id)
	{
		$product = $entityManager->getRepository(Product::class)->find($id);
		//dd($product->getCategory()->getName());//wtedy pobiera rowniez z tabeli dolaczonej category


		return $this->render('product/show.html.twig', [
			'product' => $product,
			'pageTitle' => 'Informacje o produkcie id: '.$id,
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
