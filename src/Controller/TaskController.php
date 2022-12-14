<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use App\Form\TaskSearchType;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
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
	private TaskRepository $taskRepository;
	public array $priorityArray;                    //lista priorytetow
	public array $priorityBgArray;
	private int $defaultPerPage = 25;               //domyślna ilość zadań na stronie
	private string $defaultSort = 'id DESC';        //domyślne sortowanie

	public function __construct(EntityManagerInterface $entityManager, Environment $twig)
	{
		$this->entityManager = $entityManager;
		$this->taskRepository = $entityManager->getRepository(Task::class);

		$twig->addGlobal('current_controller', 'task');

		//zmienic na enum
		$this->priorityArray = [
			3 => 'Bardzo ważne',
			2 => 'Ważne',
			0 => 'Zwykłe',
			1 => 'Mało ważne'
		];

		$this->priorityBgArray = [
			3 => 'danger',
			2 => 'danger',
			0 => '',
			1 => 'light'
		];
	}

	#[Route('/tasks', name: 'app_task_show_list')]
	public function showAll(Request $request): Response
	{
		$perPage = $request->query->getInt('per-page', $this->defaultPerPage);
		$sort = $request->query->get('sort') ?? $this->defaultSort;

		$queryBuilder = $this->taskRepository->createTaskListQueryBuilder($sort);

		$pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
			new QueryAdapter($queryBuilder),
			$request->query->getInt('page', 1),
			$perPage
		);

		$formTaskSearch = $this->createForm(TaskSearchType::class, null, [
			'action' => $this->generateUrl('app_task_search'),
			'method' => 'GET'
		]);

		//info renderForm podobnie dziala jak render()

		$resultArray = $this->buildResultArray(
			$pagerfanta, $pagerfanta->haveToPaginate(), $sort, $perPage, $this->priorityArray, $this->priorityBgArray, '',
			'',$formTaskSearch, '', null
		);
		return $this->renderForm('task/list.html.twig', $resultArray);
	}

	#[Route('/task/new', name: 'app_task_new')]
	public function new(Request $request): Response
	{
		$form = $this->createForm(TaskType::class);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$data = $form->getData();
			$result = $this->taskRepository->addTask($data);

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

		$perPage = $request->query->getInt('per-page', $this->defaultPerPage);
		$sort = $request->query->get('sort') ?? $this->defaultSort;

		$formTaskSearch = $this->createForm(TaskSearchType::class, null, ['action' => $this->generateUrl('app_task_search'), 'method' => 'GET']);
		$formTaskSearch->handleRequest($request);

		if ($formTaskSearch->isSubmitted()) {// && $formTaskSearch->isValid()
			$data = $formTaskSearch->getData(); //question - sprawdzic $data
			$searchBadgeList = $this->taskRepository->generateSearchBadge($request);

			$tasks = $this->taskRepository->findTasksFromRequest($request);
		}

		$resultArray = $this->buildResultArray(
			'', false, $sort, $perPage, $this->priorityArray, $this->priorityBgArray,
			$request->get('task_search')['title'] ??  '',
			$request->get('search_in_description') ?? '',
			$formTaskSearch, '', null
		);

		return $this->renderForm('task/list.html.twig', $resultArray);
	}

	//question - zasady SOLID w tejh metodzie zostyały złamane? dla każdej akcji grupowej osobna metoda?
	#[Route('/task/group_action', name: 'app_task_group_action')]//, methods: 'POST'
	public function groupAction(Request $request, Security $security)
	{

		$group_action_name = $request->get('group_action_name');

		//$request->get('id')
		$checkedTask = $request->get('task');

		if ($group_action_name === 'done') {

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

		} else if ($group_action_name === 'delete') {

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
	public function showAllFromCat(Request $request, int $cat): Response
	{
		$perPage = $request->query->getInt('per-page', $this->defaultPerPage);
		$sort = $request->query->get('sort') ?? $this->defaultSort;

		$tasks = $this->taskRepository->findBy(['Category' => $cat]);

		$formTaskSearch = $this->createForm(TaskSearchType::class, null, [
			'action' => $this->generateUrl('app_task_search'),
			'method' => 'GET',
		]);

		//info tytmczasoro renderForm
		$resultArray = $this->buildResultArray(
			$tasks, false, $sort, $perPage, $this->priorityArray, $this->priorityBgArray, '',
			'',$formTaskSearch, '', $cat
		);

		return $this->renderForm('task/list.html.twig', $resultArray);
	}

	//metoda budująca tablicę wyników
	private function buildResultArray(
		$pager,$haveToPaginate,$sort,$perPage,$priorityArray,$priorityBgArray,$searchPhrase,$searchInDesc,
		$ftSearch,$searchBadgeList, $category
	): array
	{
		return [
			'tasksPager' => $pager,
			'haveToPaginate' => $haveToPaginate,
			'sort' => $sort,
			'per_page' => $perPage,
			'priority_array' => $priorityArray,
			'priority_bg_array' => $priorityBgArray,
			'search_phraze' => $searchPhrase,
			'search_in_description' => $searchInDesc,
			'form_task_search' => $ftSearch,
			'search_badge_list' => $searchBadgeList,
			'category_id' => $category,
		];
	}

}
