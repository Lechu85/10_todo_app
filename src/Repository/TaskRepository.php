<?php

namespace App\Repository;

use App\Config\TaskStatus;
use App\Entity\Task;
use App\Entity\TaskCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
	private EntityManagerInterface $entityManager;
	private ValidatorInterface $validator;

	public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
	{
		parent::__construct($registry, Task::class);
		$this->entityManager = $entityManager;
		$this->validator = $validator;
	}

	public function createTaskListQueryBuilder(string $sort): QueryBuilder
	{
		//Rozbijamy na elementy
		$sort_explode = explode(' ', $sort);

        return $this->createQueryBuilder('t')
	        ->orderBy('t.'.$sort_explode[0], $sort_explode[1]);

	}
	
	public function addTask($data): array
	{
		//z formularza leci objekt, zapi tablica
		if (is_object($data)) {
			$data = $data->toArray();
		}

		$task = new Task();

		$task->setTitle($data['title']);

		if (!empty($data['description']))
			$task->setDescription($data['description']);

		if (!empty($data['status'])) {
			$task->setStatus($data['status']);
		} else {
			$task->setStatus(TaskStatus::Nowe);//tymczasowo
		}

		if (!empty($data['category'])) {
			$task->setCategory($data['category']);
		} else {
			//todo Przemysleć, skonsultować
			$taskCategory = $this->entityManager->getRepository(TaskCategory::class)->findOneBy(['id' => 1]);
			$task->setCategory($taskCategory);
		}
		$task->setCreatedAt(new \DateTimeImmutable());

		$errors = $this->validator->validate($task);

		if (count($errors) > 0) {

			$formatedViolationList = [];
			$formatedViolationListString = '';
			foreach($errors as $error) {
				//$formatedViolationList[] = array($error->getPropertyPath() => $error->getMessage());
				$formatedViolationList[$error->getPropertyPath()][] = $error->getMessage();
				$formatedViolationListString .= "\n - [".$error->getPropertyPath().'] '.$error->getMessage();
			}

			return array(
				'msg' => 'Blad walidacji' . $formatedViolationListString,
				'status' => 400, //bad request
				'flash_status' => 'error',
			);

		}


		$this->entityManager->persist($task);
		$this->entityManager->flush();

		//todo return bool is it correct?
		$this->countTasksInCategory();

		return array(
			'msg' => 'Zadanie zostalo utworzone! ID:'.$task->getId(),
			'status' => 201,
			'flash_status' => 'success',
		);

	}


	public function updateTask(?Task $task, $request): array
	{

		if ($task == null) {
			return array(
				'msg' => 'Nie znaleziono produktu do edycji',
				'status' => 404,
				'flash_status' => 'error',
			);

		} else {
			$data = Json_decode($request->getContent(), true);

			if (!empty($data['title'])) $task->setTitle($data['title']);
			if (!empty($data['description'])) $task->setDescription($data['description']);
			if (!empty($data['status'])) $task->setStatus($data['status']);
			$task->setUpdatedAt(new \DateTimeImmutable());

			$this->entityManager->persist($task);
			$this->entityManager->flush();

			//todo return bool is it correct?
			$this->countTasksInCategory();//update taskCount

			return array(
				'msg' => 'Zadanie ID: '.$task->getId().' zostalo uaktualnione',
				'status' => 200,
				'flash_status' => 'success',
			);

		}


	}


	public function findTasksFromRequest(Request $request): array
	{

		$searchPhraze = $request->get('task_search')['title'] ?? ''; //info this input name is array
		$taskSearchRequest = $request->get('task_search');
		$search_in_description = $request->get('search_in_description');

		$queryBuilder = $this->entityManager->createQueryBuilder();

		//todo Title nie zawszejest podane
		$qb = $queryBuilder->select('t')
			->from('App:Task', 't')
			->where('t.title LIKE :searchPhraze')
			->orderBy('t.id', 'DESC')
			->setParameter('searchPhraze', '%'.$searchPhraze.'%');

		if (isset($search_in_description) && $search_in_description == 1) {
			$qb->orWhere('t.description LIKE :searchPhraze');
		}

		foreach($taskSearchRequest as $fieldName => $fieldValue) {

			if (!empty($fieldValue) && $fieldName !== '_token') {

				if ($fieldName === 'dueDate' || $fieldName === 'createdAt' || $fieldName === 'doneAt') {
					//if (is_array($fieldValue)) {//pole DateTimeFromToType

					if (!empty($fieldValue['From']) && !empty($fieldValue['To'])) {
						$qb->andWhere('t.' . $fieldName . ' <= :' . $fieldName . 'From AND t.' . $fieldName . ' >= :' . $fieldName . 'To')
							->setParameter($fieldName . 'From', $fieldValue['From'])
							->setParameter($fieldName . 'To', $fieldValue['To']);;

					} else if (!empty($fieldValue['To'])) {
						$qb->andWhere('t.' . $fieldName . ' >= :' . $fieldName . 'To')
							->setParameter($fieldName . 'To', $fieldValue['To']);

					} else if (!empty($fieldValue['From'])) {
						$qb->andWhere('t.' . $fieldName . ' <= :' . $fieldName . 'From')
							->setParameter($fieldName . 'From', $fieldValue['From']);
					}

				} else if ($fieldName === 'status') {//status wysyłamy jako tablice

					$andwhere = '';
					if (is_array($fieldValue) && count($fieldValue)>0) {
						foreach ($fieldValue as $fieldValueFromArray) {
							$andwhere .= 't.' . $fieldName . ' LIKE :' . $fieldName.$fieldValueFromArray.' OR ';

							//czy mozna najpierw parametr, pozniej kod?
							$qb->setParameter($fieldName.$fieldValueFromArray, '%' . $fieldValueFromArray . '%');
						}

						$qb->andWhere(rtrim($andwhere, 'OR '));

					}





				} else if ((isset($search_in_description) && $search_in_description == 1) && $fieldName === 'title' ) {
					//todo dla zaawansowanej wyszukiwarki, chowamy pole +Opis w głównej cześci
					$qb->andWhere('t.title LIKE :title OR t.description LIKE :title')
						->setParameter('title', '%'.$fieldValue.'%');

				} else { //Pozostałe pola
					$qb->andWhere('t.'.$fieldName.' LIKE :'.$fieldName)
						->setParameter($fieldName, '%'.$fieldValue.'%');

				}

			}

		}//endforeach

		return $qb
				->getQuery()
				->execute();
	}


	/**
	 * Metoda generuje liste wstążek z parametrami, które użytkownik aktualnie wyszukuje.
	 * @return string
	 */
	public function generateSearchBadge(Request $request): string
	{
		$generatedHtml = '';
		$taskSearchRequest = $request->get('task_search');

		foreach($taskSearchRequest as $fieldName => $fieldValue ) {


			if ($fieldName === 'title' ||
				$fieldName === '_token' ||
				empty($fieldValue)) {
				continue;
			}

			//question   czy taki zapis nazw pól jest ok? jak z tłumaczneiem? czy można pobrać z klasy formularza*/
			// jak najlepiej pobrac tutaj docelowe wartosci. naz\wa kategorii , priorytet itd?
			// czy mozna utworzyć tablice ze statusami czy priorytetami?

			$fieldLabel = [
				"dueDate" => 'Realizacja',
				"createdAt" =>  'Utworzono',
				"doneAt" =>  'Wykonane',
				"description" => 'Opis',
				"status" => 'Status',
				"prioryty" => 'Priorytet',
				"category" => 'Kategoria',
			];

			//DateTimeFromToType
			//if (is_array($fieldValue)) {
			if ($fieldName === 'dueDate' || $fieldName === 'createdAt' || $fieldName === 'doneAt') {

				if (!empty($fieldValue['From']) && !empty($fieldValue['To'])) {
					$generatedHtml .= '<span class="badge text-bg-secondary"><span class="fw-normal">' . $fieldLabel[$fieldName] . '</span>';

					if (!empty($fieldValue['From'])) {
						$generatedHtml .= '<span class="fw-normal"> od: </span>' . date('Y-m-d', strtotime($fieldValue['From']));
					}
					if (!empty($fieldValue['To'])) {
						$generatedHtml .= '<span class="fw-normal"> do: </span>' . date('Y-m-d', strtotime($fieldValue['To']));
					}

					$generatedHtml .= ' <a href="#" onClick="alert(\'Soon :) \'); return false;" class="text-white">x</a></span> ';
				}

			//zwykłe pole
			} else if ($fieldName === 'status') {//status wysyłamy jako tablice
				//
			} else {

				if (strlen($fieldValue) > 15) {
					$fieldValue = substr($fieldValue, 0,  15).'...';
				}

				$generatedHtml .= '<span class="badge text-bg-secondary"><span class="fw-normal">'.$fieldLabel[$fieldName].':</span> '.$fieldValue.' <a href="#" onClick="alert(\'Soon :) \'); return false;" class="text-white">x</a></span> ';
			}
		}

		return $generatedHtml;
	}

	/**
	 * Metoda przelicza ilości zadań w poszczególnych kategoriach i uaktualnia taskCount
	 * @return bool
	 */
	public function countTasksInCategory(): void
	{

		$query = $this->entityManager->createQuery('
			UPDATE App:TaskCategory tc 
			SET tc.taskCount = 
				(SELECT COUNT (t.id) 
				FROM App:Task t
				WHERE t.Category = tc.id) 
			');

		$query->execute();

		//return $this->doctrine->getRepository(Auto::class)
		//	->count(['dealer' => $dealerId]);


	}

	/**
	 * Pobiera wszystkie zamknięte w dniu obecnym zadania
	 */
	public function findAllTodayDoneTasks():array
	{
		return $this->createQueryBuilder('t')
			->andWhere('t.doneAt LIKE :today')
			->setParameter('today', date('Y-m-d').'%')
			->groupBy('t.user') //aby nie powielać tych samych useró kilka razy podobnie jak distinct
			->getQuery()
			->getResult();
	}

	/**
	 * Pobiera wszystkie zamknięte w dniu obecnym zadania, przez wskazanego usera
	 */
	public function findAllTodayDoneTasksByUser($user): array
	{
		return $this->createQueryBuilder('t')
			->andWhere('t.user = :user')
			->andWhere('t.doneAt LIKE :today')
			->setParameter('user', $user)
			->setParameter('today', date('Y-m-d').'%')
			->getQuery()
			->getResult();
	}
}
