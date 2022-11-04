<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AddHelper2
{

	public function add(int $a, int $b): int
	{

		$result = $a + $b;

		return $result;
	}
}