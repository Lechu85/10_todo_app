<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Enum\TaskEnum;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskApiController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private $taskRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->taskRepository = $entityManager->getRepository(Task::class);
    }

	#[Route('/api/tasks', name: 'api_task_get_list', methods: 'GET')]
	public function getAll(): JsonResponse
	{
		$tasks = $this->taskRepository->findAll();

		$data = [];

		foreach ($tasks as $task) {
			$data[] = [
				'id' => $task->getId(),
				'title' => $task->getTitle(),
				'description' => $task->getDescription(),
			];
		}

		return new JsonResponse($data, Response::HTTP_OK);
	}

	#[Route('/api/task/{id}', name: 'api_task_get_one', methods: 'GET')]
	public function getOne(int $id): JsonResponse
	{
		$task = $this->taskRepository->findOneBy(['id' => $id]);

		if ($task == null) {
			return new JsonResponse(['status' => 'Wskazane zadanie nie istnieje'], Response::HTTP_NOT_FOUND);
		} else {
			$data = [
				'id' => $task->getId(),
				'title' => $task->getTitle(),
				'description' => $task->getDescription(),
                'status' => TaskEnum::NEW
			];

			return new JsonResponse($data, Response::HTTP_OK);
		}
	}

    #[Route('/api/task', name: 'api_task_add', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
	    $data = json_decode($request->getContent(), true);
	    $error_json = $this->jsonLastErrorMsg();

		if (empty($error_json)) {
			$result = $this->taskRepository->addTask($data);
			return new JsonResponse(['status' => $result['msg']], $result['status']);

		} else {
			return new JsonResponse(['status' => $error_json], 400);//bad request
		}
    }

	//question GDZIE umieścic tą walidacje JSON? osobny servis?
	private function jsonLastErrorMsg() {

		//todo zamienic na match - php 8.1

		switch (json_last_error()) {
			case JSON_ERROR_NONE:
				$error_json = ''; //'No errors';
				break;
			case JSON_ERROR_DEPTH:
				$error_json = 'Maximum stack depth exceeded';
				break;
			case JSON_ERROR_STATE_MISMATCH:
				$error_json = 'Underflow or the modes mismatch';
				break;
			case JSON_ERROR_CTRL_CHAR:
				$error_json = 'Unexpected control character found';
				break;
			case JSON_ERROR_SYNTAX:
				$error_json = 'Syntax error, malformed JSON';
				break;
			case JSON_ERROR_UTF8:
				$error_json = 'Malformed UTF-8 characters, possibly incorrectly encoded';
				break;
			default:
				$error_json = 'Unknown error';
				break;
		}

		return $error_json;

	}

	#[Route('/api/task/{id}', name: 'api_task_edit', methods: 'PUT')]
	public function edit(Request $request, int $id): JsonResponse
	{
		$task = $this->taskRepository->findOneBy(['id' => $id]);
		$result = $this->taskRepository->updateTask($task, $request);

		return new JsonResponse(['status' => $result['msg']], $result['status']);
	}

    #[Route('/api/task/{id}', name: 'api_task_remove', methods: 'DELETE')]
    public function remove(int $id): JsonResponse
    {
        $task = $this->taskRepository->findOneBy(['id' => $id]);
        if ($task == null) {
            return new JsonResponse(['status' => 'Wskazane zadanie nie istnieje'], Response::HTTP_NOT_FOUND);
        } else {
            $this->entityManager->remove($task);
            $this->entityManager->flush();

	        $this->taskRepository->countTasksInCategory();//update taskCount

            return new JsonResponse(['status' => 'Zadanie zostalo usuniete.'], Response::HTTP_OK);
        }
    }

}
