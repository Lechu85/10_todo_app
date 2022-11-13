<?php

namespace App\Controller;

use App\Entity\TaskCategory;
use App\Form\TaskCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskCategoryController extends AbstractController
{

	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	#[Route('/task/new_category', name: 'app_task_category_new')]
	public function newCategory(Request $request): Response
	{
		$taskCategory = new TaskCategory();

		$form = $this->createForm(TaskCategoryType::class, $taskCategory);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {


			$this->entityManager->persist($taskCategory);
			$this->entityManager->flush();

			$this->addFlash('success', 'Kategoria zostala dodana');

			return $this->redirectToRoute('app_task_show_list');
		}

		return $this->renderForm('task/new_category.html.twig', [
			'form' => $form,
		]);
	}

	#[Route('/task/categories', name: 'app_task_category_show_list')]
	public function showAllCategory(): Response
	{
		$taskCategoryRepositories = $this->entityManager->getRepository(TaskCategory::class);
		$taskCategories = $taskCategoryRepositories->findAll();

		return $this->render('task/category_list.html.twig', [
			'taskCategories' => $taskCategories
		]);

	}
}