<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Service\TaskCategoryMenu;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{

	private EntityManagerInterface $entityManager;
	private $taskRepository;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->taskRepository = $entityManager->getRepository(Task::class);
	}

	#[Route('/tasks', name: 'app_task_show_list')]
    public function showAll(TaskCategoryMenu $taskCategoryMenu): Response
    {



	    $taskCategoryMenu->generateTaskCategoryMenu();


		$tasks = $this->taskRepository->findAll();




        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

	#[Route('/task/new', name: 'app_task_new')]
	public function new(Request $request): Response
	{
		$form = $this->createForm(TaskType::class);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			//dd($form->get('agreeTerms')->getData());

			$data = $form->getData();

			$result = $this->taskRepository->addTask($data);

			//$this->entityManager->persist($task);
			//$this->entityManager->flush();

			$this->addFlash($result['flash_status'], $result['msg']);

			return $this->redirectToRoute('app_task_show_list');
		}

		return $this->renderForm('task/new.html.twig', [
			'form' => $form,
		]);
	}

	#[Route('/task/edit/{id}', name: 'app_task_edit')]
	public function edit(Task $task, Request $request): Response
	{
		$form = $this->createForm(TaskType::class,$task);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$task = $form->getData();


			$this->entityManager->persist($task);
			$this->entityManager->flush();


			$this->addFlash('success', 'Your form has been edit.');

			return $this->redirectToRoute('app_task_show_list');
		}

		return $this->renderForm('task/edit.html.twig', [
			'form' => $form,
		]);
	}

	#[Route('/task/remove/{id}', name: 'app_task_remove')]
	public function remove(int $id)
	{
		$task = $this->taskRepository->findOneBy(['id' => $id]);
		if ($task == null) {
			$this->addFlash('error', 'Zadanie o ID: '.$id.' juz nie istnieje w bazie.');
		} else {

			$this->entityManager->remove($task);
			$this->entityManager->flush();

			$this->addFlash('error', 'Zadanie od ID: '.$id.' zostało usunięte');

		}
		return $this->redirectToRoute('app_task_show_list');
	}

	#[Route('/task/checkbox_test', name: 'app_task_checkbox_test', methods: 'POST')]
	public function groupAction(Request $request)
	{

		$group_action_name = $request->get('group_action_name');
		$checkedTask = $request->get('task');

		if ($group_action_name == 'done') {

			$queryBuilder = $this->entityManager->createQueryBuilder();

			$query = $queryBuilder->update('App:Task', 't')
				->set('t.doneAt', ':doneAt')
				->set('t.description', ':description')
				->where('t.id IN ( :idList ) ')
				->setParameter('doneAt', new \DateTime(), Types::DATETIME_MUTABLE)
				->setParameter('idList', $checkedTask)
				->getQuery();

			$query->execute();

			$this->addFlash('success', 'Zadania zostały wykonane');

		} else if ($group_action_name == 'delete') {

			$queryBuilder = $this->entityManager->createQueryBuilder();

			$query = $queryBuilder->delete('App:Task', 't')
				->where('t.id IN ( :idList ) ')
				->setParameter('idList', $checkedTask)
				->getQuery();

			$query->execute();

			$this->addFlash('success', 'Zadania zostały usunięte');

		} else {
			throw new Exception('Niepoprawny parametr');
		}

		return $this->redirectToRoute('app_task_show_list');

	}

}
