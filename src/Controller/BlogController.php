<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\BlogCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blogs', name: 'blogs_list')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $blogs = $entityManager->getRepository(Blog::class)->findAll();
		dd($blogs);

		//return new Response();

    }

	#[Route('/blog/{id}')]
	public function show(EntityManagerInterface $entityManager, int $id): Response
	{

		$blog = $entityManager->getRepository(Blog::class)->find($id);

		dd($blog, $blog->getCategory()->getName());
		//return new Response('');
	}

	#[Route('/blog/add')]
	public function add(EntityManagerInterface $entityManager): Response
	{
		$blog = new Blog();

		$blogCategory = new BlogCategory();
		$blogCategory->setName('Nowa kategoria');

		$blog->setTitle('Pierwszy blog w bazie ')
			->setDescription('Nasz blog, nasz blog, nasz blog, nasz blog.')
			->setAuthor('Leszek Leszek')
			->setActive(true)
			->setDate(new \DateTime())
			->setCategory($blogCategory);


		$entityManager->persist($blogCategory);
		$entityManager->persist($blog);
		$entityManager->flush();

		return new Response('<html><body>New art is saved ' . $blog->getId() . '</body></html>');

	}

	#[Route('/blog/edit/{id}')]
	public function update(EntityManagerInterface $entityManager, int $id): Response
	{
		$blog = $entityManager->getRepository(Blog::class)->find($id);

		if (!$blog) {
			return new Response('Brak artykułu o podanym id');
		}

		$blog->setTitle('Poprawiony tytuł');

		$entityManager->persist($blog);
		$entityManager->flush();

		return $this->redirectToRoute('blogs_list');

	}

	#[Route('/blog/delete/{id}')]
	public function delete(EntityManagerInterface $entityManager, int $id): Response
	{
		$blog = $entityManager->getRepository(Blog::class)->find($id);

		if (!$blog) {
			return new Response('Brak artykułu o podanym id');
		}

		$entityManager->remove($blog);
		$entityManager->flush();

		return $this->redirectToRoute('blogs_list');
	}

}
