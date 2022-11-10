<?php

namespace App\MessageHandler;

use App\Message\SmsNotification;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SmsNotificationHandler
{

	private $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function __invoke(SmsNotification $message)
	{

		sleep(3);
		$user = $this->userRepository->find($message->getUserId());


		file_put_contents(
			'c:/_symfony_messenger/sms.txt',
			'Cześć '.$user->getName().'. '.$message->getContent());

		dump($message->getContent());
		die();
	}
}