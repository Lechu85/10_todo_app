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
	private $taskRepository;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->taskCategoryRepository = $entityManager->getRepository(TaskCategory::class);
	}

	#[Route('/task/new_category', name: 'app_task_category_new')]
	public function newCategory(Request $request): Response
	{
		$category = new TaskCategory();

		$form = $this->createForm(TaskCategoryType::class, $category);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {


			$this->entityManager->persist($category);
			$this->entityManager->flush();

			$this->addFlash('success', 'Kategoria zostala dodana');

			return $this->redirectToRoute('app_task_category_show_list');
		}

		return $this->renderForm('task/category_form.html.twig', [
			'form' => $form,
		]);
	}

	//todo usuniecie kategorii, powodujue usuniecie zadania -
	#[Route('/task/remove_category/{id}', name: 'app_task_category_remove')]
	public function remove(int $id)
	{
		$category = $this->taskCategoryRepository->findOneBy(['id' => $id]);
		if ($category == null) {
			$this->addFlash('error', 'Grupa o ID: '.$id.' juz nie istnieje w bazie.');
		} else {

			$this->entityManager->remove($category);
			$this->entityManager->flush();

			$this->addFlash('error', 'Grupa od ID: '.$id.' została usunięta');

		}
		return $this->redirectToRoute('app_task_category_show_list');
	}


	#[Route('/task/edit_category/{id}', name: 'app_task_category_edit')]
	public function editCategory(TaskCategory $category, Request $request, int $id): Response
	{

		$form = $this->createForm(TaskCategoryType::class, $category);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$category = $form->getData();

			$this->entityManager->persist($category);
			$this->entityManager->flush();

			$this->addFlash('success', 'Kategoria została zapisana');

			return $this->redirectToRoute('app_task_category_show_list');
		}

		return $this->renderForm('task/category_form.html.twig', [
			'form' => $form,
			'id' => $id,
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