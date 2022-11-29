<?php

namespace App\DataFixtures;

use App\Config\TaskStatus;
use App\Entity\Category;
use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
	private UserPasswordHasherInterface $passwordHasher;

	public function __construct(UserPasswordHasherInterface $passwordHasher)
	{
		$this->passwordHasher = $passwordHasher;
	}

	//todo przereobic na fabryke
	public function load(ObjectManager $manager): void
    {

	    $user = new User();
	    $user->setEmail('leszek.chopcian@gmail.com');
	    $user->setPassword($this->passwordHasher->hashPassword($user,'test123'));
	    $user->setName('Andrzej');
	    $user->setRoles(["ROLE_SUPERADMIN"]);
	    $user->setIsVerified(true);
	    $user->agreeToTerms();
	    $manager->persist($user);


	    $user = new User();
		$user->setEmail('leszek@todoapp.pl');
		$user->setPassword($this->passwordHasher->hashPassword($user,'test123'));
	    $user->setName('Leszek ');
	    $user->setRoles(["ROLE_ADMIN"]);
	    $user->setIsVerified(true);
		$user->agreeToTerms();
	    $manager->persist($user);


		$category = new TaskCategory();
		$category->setName('Zadania firmowe');
		$category->setDescription('Główne zadania do wykonania w pracy.');
		$category->setTaskCount(30);
		$manager->persist($category);


	    // create 20 products! Bam!
	    for ($i = 0; $i < 30; $i++) {
		    $task = new Task();
		    $task->setTitle('Zadanie do wykonania '.$i);
			$task->setUser($user);
			$task->setCategory($category);
			$task->setDueDate( new \DateTime('tomorrow') );
		    $task->setDescription('Przykładowy opis ');
			$task->setStatus(TaskStatus::Nowe);
		    $task->setCreatedAt( new \DateTime() );
			$task->setPrioryty(rand(0, 3));

		    $manager->persist($task);
	    }


	    $category = new TaskCategory();
	    $category->setName('Zakupy do domu');
		$category->setDescription('Zadania do wykonania po pracy');
		$category->setTaskCount(8);
	    $manager->persist($category);


	    // create 20 products! Bam!
	    for ($i = 0; $i < 8; $i++) {
		    $task = new Task();
		    $task->setTitle('Zrób zakupy w Biedronce '.$i);
		    $task->setUser($user);
		    $task->setCategory($category);
		    $task->setDueDate( new \DateTime('tomorrow') );
		    $task->setDescription('Przykładowy opis ');
		    $task->setStatus(TaskStatus::Nowe);
		    $task->setCreatedAt( new \DateTime() );
		    $task->setPrioryty(rand(0, 3));

		    $manager->persist($task);
	    }


	    $category = new TaskCategory();
	    $category->setName('Załatwić po pracy');
	    $category->setDescription('Zadania do wykonania po pracy');
	    $category->setTaskCount(0);
	    $manager->persist($category);


	    $user = new User();
	    $user->setEmail('dawid@todoapp.pl');
	    $user->setPassword($this->passwordHasher->hashPassword($user,'test123'));
	    $user->setName('Dawid');
	    $user->setRoles([]);
	    $user->setIsVerified(true);
	    $user->agreeToTerms();
	    $manager->persist($user);


	    $user = new User();
	    $user->setEmail('andrzej@todoapp.pl');
	    $user->setPassword($this->passwordHasher->hashPassword($user,'test123'));
	    $user->setName('Andrzej');
	    $user->setRoles(["ROLE_USER"]);
	    $user->setIsVerified(true);
	    $user->agreeToTerms();
	    $manager->persist($user);


	    $user = new User();
	    $user->setEmail('tadeusz.tadfeusz@gmail.com');
	    $user->setPassword($this->passwordHasher->hashPassword($user,'test123'));
	    $user->setName('Andrzej');
	    $user->setRoles(["ROLE_MODER"]);
	    $user->setIsVerified(true);
	    $user->agreeToTerms();
	    $manager->persist($user);


        $manager->flush();
    }
}
