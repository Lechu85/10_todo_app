<?php

namespace App\Command;

use App\Repository\TaskRepository;
use App\Service\SendingEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:done-task-daily-report:send',
    description: 'Sends emails with a daily summary of closed tasks',
)]
class DoneTaskDailyReportSendCommand extends Command
{

	private TaskRepository $taskRepository;
	private SendingEmail $mailer;

	public function __construct(TaskRepository $taskRepository, SendingEmail $mailer)
	{
		parent::__construct(null);
		$this->taskRepository = $taskRepository;
		$this->mailer = $mailer;
	}

	protected function configure(): void
    {
        $this
	        ->setDescription('Send daily reports to users')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
	    $users = $this->taskRepository->findAllTodayDoneTasks();

	    $io->progressStart(count($users)); //count of element for progres bar

	    foreach ($users as $user) {
		    $io->progressAdvance();

			if (empty($user->getUser())) {
				continue;
			}

		    $tasks = $this->taskRepository
			    ->findAllTodayDoneTasksByUser($user->getUser());

		    //Skip users who do not done task this day
		    if(count($tasks) === 0) {
			    continue;
		    }
		    $this->mailer->sendUserDoneTaskDailyReportMessage($user->getUser(), $tasks);

	    }

	    $io->progressFinish();

	    $io->success('Daily reports were sent to users!');

	    return 0;

    }
}
