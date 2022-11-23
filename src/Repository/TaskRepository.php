<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
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


	public function addTask($data): array
	{
		//z formularza leci objekt, zapi tablica
		if (is_object($data)) {
			$data = $data->toArray();
		}

		//dump('po przetworzeniu', $data);

		$task = new Task();

		$task->setTitle($data['title'])
			->setDescription($data['description'])
			->setStatus($data['status'])
			->setCreatedAt(new \DateTimeImmutable());

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

			return array(
				'msg' => 'Zadanie ID: '.$task->getId().' zostalo uaktualnione',
				'status' => 200,
				'flash_status' => 'success',
			);

		}
	}

	public function findTasksFromRequest(Request $request, ?int $search_in_description): array
	{

		$searchPhraze = $request->get('task_search')['title']; //info this input name is array



		//dump($request->get('task_search'));

//		$query = $this->entityManager->createQuery(
//			'SELECT t
//            FROM App:Task t
//            WHERE t.task LIKE :title
//            ORDER BY t.id DESC'
//		)->setParameter('title', '%'.$searchPhraze.'%');
//
//		return $query->getResult();//getResult to alias dla executre(). w execute mozna podac parametry

		$queryBuilder = $this->entityManager->createQueryBuilder();

		$qb = $queryBuilder->select('t')
			->from('App:Task', 't')
			->where('t.title LIKE :searchPhraze')
			//->orWhere('t.description LIKE :searchPhraze')
			->orderBy('t.id', 'DESC')
			->setParameter('searchPhraze', '%'.$searchPhraze.'%');

		if (isset($search_in_description) && $search_in_description == 1) {
			$qb->orWhere('t.description LIKE :searchPhraze');
		}

		foreach($request->get('task_search') as $fieldName => $fieldValue) {
			//dump($fieldName .' - '. $fieldValue);

			if ($fieldName === 'dueDateFrom' && !empty($fieldValue)) {
				$qb
					->andWhere('t.dueDate <= :dueDateFrom')
					->setParameter('dueDateFrom', $fieldValue);
			}

			else if ($fieldName === 'dueDateTo' && !empty($fieldValue)) {
				$qb
					->andWhere('t.dueDate >= :dueDateTo')
					->setParameter('dueDateTo', $fieldValue);
			}

				//info createdAt
			else if ($fieldName === 'createdAtFrom' && !empty($fieldValue)) {
				$qb
					->andWhere('t.createdAt <= :createdAtFrom')
					->setParameter('createdAtFrom', $fieldValue);
			}

			else if ($fieldName === 'createdAtTo' && !empty($fieldValue)) {
				$qb
					->andWhere('t.createdAt >= :createdAtTo')
					->setParameter('createdAtTo', $fieldValue);
			}

			else {
				$qb
					->andWhere('t.'.$fieldName.' <= :'.$fieldName)
					->setParameter($fieldName, $fieldValue);
			}


		}//endforeach
dd($qb->getQuery());
		return $qb
				->getQuery()
				->execute();
	}
}
