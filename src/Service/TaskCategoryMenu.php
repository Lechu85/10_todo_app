<?php

namespace App\Service;

use App\Entity\TaskCategory;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class TaskCategoryMenu
{
	private EntityManagerInterface $entityManager;
	private Environment $twig;

	public function __construct(EntityManagerInterface $entityManager, Environment $twig)
	{
		$this->entityManager = $entityManager;
		$this->twig = $twig;
	}

	public function generateTaskCategoryMenu()
	{

		$taskCategories = $this->entityManager->getRepository(TaskCategory::class)->findAll();
		$this->twig->addGlobal('taskCategories', $taskCategories);
	}
}