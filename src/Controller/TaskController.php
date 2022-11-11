<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

    #[Route('/task', name: 'app_task')]
    public function index(EntityManagerInterface $entityManager): Response
    {
		$taskRepository = $entityManager->getRepository(Task::class);
		$tasks = $taskRepository->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

	#[Route('/task/new', name: 'app_task_new')]
	public function new(Request $request, EntityManagerInterface $entityManager): Response
	{
		$form = $this->createForm(TaskType::class);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			//dd($form->get('agreeTerms')->getData());

			$task = $form->getData();
			$entityManager->persist($task);
			$entityManager->flush();

			$this->addFlash('success', 'Your form has been saved.');

			return $this->redirectToRoute('app_task');
		}

		return $this->renderForm('task/new.html.twig', [
			'form' => $form,
		]);
	}

	#[Route('/task/edit/{id}', name: 'app_task_edit')]
	public function edit(Task $task, Request $request, EntityManagerInterface $entityManager): Response
	{
		$form = $this->createForm(TaskType::class,$task);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$task = $form->getData();
			$entityManager->persist($task);
			$entityManager->flush();

			$this->addFlash('success', 'Your form has been edit.');

			return $this->redirectToRoute('app_task');
		}

		return $this->renderForm('task/edit.html.twig', [
			'form' => $form,
		]);
	}

}
