<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskSearchType;
use App\Form\TaskType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class TaskController extends AbstractController
{

	private EntityManagerInterface $entityManager;
	private $taskRepository;

	public function __construct(EntityManagerInterface $entityManager, Environment $twig)
	{
		$this->entityManager = $entityManager;
		$this->taskRepository = $entityManager->getRepository(Task::class);

		$twig->addGlobal('current_controller', 'task');
	}


	#[Route('/tasks', name: 'app_task_show_list')]
    public function showAll(): Response
    {
		$tasks = $this->taskRepository->findAll();

	    $formTaskSearch = $this->createForm(TaskSearchType::class);

		//info renderForm podobnie dziala jak render()
        return $this->renderForm('task/list.html.twig', [
            'tasks' => $tasks,
	        'search_phraze' => '',
	        'search_in_description' => '',
	        'formTaskSearch' => $formTaskSearch

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
	public function edit(Task $task, Request $request, int $id): Response
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
			'id' => $id,
		]);
	}


	#[Route('/task/show/{id}', name: 'app_task_show')]
	public function showOne(int $id): Response
	{
		$task = $this->taskRepository->findOneBy(['id' => $id]);

		return $this->render('task/show.html.twig',[
			'task' => $task,
			'id' => $id
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


	#[Route('/tasks/search/', name: 'app_task_search')]
	public function search(Request $request)
	{

		$search_phraze = $request->get('task_search')['title']; //info this input name is array
		$search_in_description = $request->get('search_in_description');

		$formTaskSearch = $this->createForm(TaskSearchType::class);
		$formTaskSearch->handleRequest($request);

		if ($formTaskSearch->isSubmitted() && $formTaskSearch->isValid()) {
			// data is an array with "name", "email", and "message" keys
			$data = $formTaskSearch->getData();
			dump('gooo',$data);

			//todo dodac tutaJ WERYFIKACJE

		}
		$tasks = $this->taskRepository->findTasksFromRequest($request, $search_in_description);



		return $this->renderForm('task/list.html.twig', [
			'tasks' => $tasks ?? '',
			'header' => 'Szukaj: '.$search_phraze,
			'search_phraze' => $search_phraze,
			'search_in_description' => $search_in_description,
			'formTaskSearch' => $formTaskSearch
		]);

	}


	#[Route('/task/group_action', name: 'app_task_group_action')]//, methods: 'POST'
	public function groupAction(Request $request, Security $security)
	{

		$group_action_name = $request->get('group_action_name');

		//$request->get('id')
		$checkedTask = $request->get('task');

		if ($group_action_name == 'done') {

			//todo przeniesc do repozytorium
			$queryBuilder = $this->entityManager->createQueryBuilder();

			$query = $queryBuilder->update('App:Task', 't')
				->set('t.doneAt', ':doneAt')
				->set('t.doneByUser', ':doneByUser')
				->where('t.id IN ( :idList ) ')
				->setParameter('doneAt', new \DateTime(), Types::DATETIME_MUTABLE)
				->setParameter('doneByUser', $security->getUser()->getId())
				->setParameter('idList', $checkedTask)
				->getQuery();

			$query->execute();

			$this->addFlash('success', 'Oznaczono jako wykonane.');

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


	#[Route('/tasks/{cat}', name: 'app_task_show_list_from_cat')]
	public function showFromCat(int $cat): Response
	{
		$tasks = $this->taskRepository->findBy(['Category' => $cat]);

		$formTaskSearch = $this->createForm(TaskSearchType::class);

		//info tytmczasoro renderForm
		return $this->renderForm('task/list.html.twig', [
			'tasks' => $tasks,
			'search_phraze' => '',
			'search_in_description' => '',
			'formTaskSearch' => $formTaskSearch,
		]);
	}

}
