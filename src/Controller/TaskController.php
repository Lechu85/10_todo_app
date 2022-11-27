<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use App\Form\TaskSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

//question - nie podoba mi się, że trzeba generować wyszukiwarke w metodzie dla listy zadań i listy z kategorii
// czy te metody jednak nie powinny być razem? jako kategorie podawać All?

class TaskController extends AbstractController
{

	private EntityManagerInterface $entityManager;
	private $taskRepository;
	public array $prioryty_array; //lista priorytetow
	public array $prioryty_bg_array;

	public function __construct(EntityManagerInterface $entityManager, Environment $twig)
	{

		$this->entityManager = $entityManager;
		$this->taskRepository = $entityManager->getRepository(Task::class);

		$twig->addGlobal('current_controller', 'task');

		//zmienic na enum
		$this->prioryty_array = [
			3 => 'Bardzo ważne',
			2 => 'Ważne',
			0 => 'Zwykłe',
			1 => 'Mało ważne'
		];

		$this->prioryty_bg_array = [
			3 => 'danger',
			2 => 'danger',
			0 => '',
			1 => 'light'
		];
	}


	#[Route('/tasks', name: 'app_task_show_list')]
    public function showAll(): Response
    {
		$tasks = $this->taskRepository->findAll();

	    $formTaskSearch = $this->createForm(TaskSearchType::class, null, [
		    'action' => $this->generateUrl('app_task_search'),
		    'method' => 'GET'
	    ]);

		//info renderForm podobnie dziala jak render()
        return $this->renderForm('task/list.html.twig', [
            'tasks' => $tasks,
			'prioryty_array' => $this->prioryty_array,
	        'prioryty_bg_array' => $this->prioryty_bg_array,
	        'form_task_search' => $formTaskSearch,
	        'search_phraze' => '',
	        'search_in_description' => '',
	        'search_badge_list' => '',

        ]);
    }


	#[Route('/task/new', name: 'app_task_new')]
	public function new(Request $request): Response
	{
		$form = $this->createForm(TaskType::class);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$data = $form->getData();
			$result = $this->taskRepository->addTask($data);

			$this->taskRepository->countTasksInCategory();//update taskCount

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
		//todo - sprawdzamy czy istnieje obiekt
		// $this->taskRepository->findOneBy(['id' => $id]);

		$form = $this->createForm(TaskType::class, $task);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$task = $form->getData();

			$this->entityManager->persist($task);
			$this->entityManager->flush();

			$this->taskRepository->countTasksInCategory();//update taskCount

			$this->addFlash('success', 'Zadanie o ID:<strong>' . $task->getId() . '</strong> zostało zapisane');

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

			$this->taskRepository->countTasksInCategory();//update taskCount

			$this->addFlash('error', 'Zadanie od ID: '.$id.' zostało usunięte');

		}
		return $this->redirectToRoute('app_task_show_list');
	}


	#[Route('/tasks/search/', name: 'app_task_search')]
	public function search(Request $request)
	{
		$searchBadgeList = '';

		$formTaskSearch = $this->createForm(TaskSearchType::class, null, ['action' => $this->generateUrl('app_task_search'), 'method' => 'GET']);
		$formTaskSearch->handleRequest($request);

		if ($formTaskSearch->isSubmitted() && $formTaskSearch->isValid()) {
			$data = $formTaskSearch->getData(); //question - sprawdzic $data
			$searchBadgeList = $this->taskRepository->generateSearchBadge($request);

			$tasks = $this->taskRepository->findTasksFromRequest($request);
		}

		return $this->renderForm('task/list.html.twig', [
			'tasks' => $tasks ?? '',
			'prioryty_array' => $this->prioryty_array,
			'prioryty_bg_array' => $this->prioryty_bg_array,
			'search_phraze' => $request->get('task_search')['title'] ??  '',
			'search_in_description' => $request->get('search_in_description') ?? '',
			'search_badge_list' => $searchBadgeList,
			'form_task_search' => $formTaskSearch
		]);

	}


	//question - zasady SOLID w tejh metodzie zostyały złamane? dla każdej akcji grupowej osobna metoda?
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
	public function showAllFromCat(int $cat): Response
	{
		$tasks = $this->taskRepository->findBy(['Category' => $cat]);

		$formTaskSearch = $this->createForm(TaskSearchType::class, null, [
			'action' => $this->generateUrl('app_task_search'),
			'method' => 'GET',
		]);

		//info tytmczasoro renderForm
		return $this->renderForm('task/list.html.twig', [
			'tasks' => $tasks,
			'prioryty_array' => $this->prioryty_array,
			'prioryty_bg_array' => $this->prioryty_bg_array,
			'category_id' => $cat,
			'search_phraze' => '',
			'search_in_description' => '',
			'form_task_search' => $formTaskSearch,
			'search_badge_list' => '',
		]);
	}

}
