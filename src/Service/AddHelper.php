<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AddHelper
{
	private Filesystem $filesystem;
	public $mailer;
	private $adminEmail;

	public function __construct(Filesystem $filesystem, MailerInterface $mailer, string $adminEmail)
	{
		$this->filesystem = $filesystem;
		$this->mailer = $mailer;
		$this->adminEmail = $adminEmail;
	}

	public function add(int $a, int $b): int
	{
		$this->filesystem->touch('testt.txt');

		$result = $a + $b;

		$email = (new Email())
			->from('hello@example.com')
			->to($this->adminEmail)

			->subject('Testowy email')
			->text('Result: ' . $result)
			->html('<p>Result: ' . $result . '</p>');

		$this->mailer->send($email);



		return $result;
	}
}