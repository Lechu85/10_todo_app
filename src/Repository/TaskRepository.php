<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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

		$task->setTask($data['task'])
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

			if (!empty($data['task'])) $task->setTask($data['task']);
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

	// /**
	//  * @return Task[] Returns an array of Task objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('t')
			->andWhere('t.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('t.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?Task
	{
		return $this->createQueryBuilder('t')
			->andWhere('t.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
