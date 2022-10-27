<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendInfoToAdmin
{

	private MailerInterface $mailer;

	public function __construct(MailerInterface $mailer)
	{
		$this->mailer = $mailer;
	}

	public function sendEmail()
	{

		//emaile odbieramy na https://mailtrap.io

		$email = (new Email())
			->from('ddd@sotech.pl')
			->to('ppp@sotech.pl')
			//->cc('cc@example.com')
			//->bcc('bcc@example.com')
			//->replyTo('fabien@example.com')
			//->priority(Email::PRIORITY_HIGH)
			->subject('Pierwszy wlasny serwis')
			->text('Pierwszy wlasny serwis z zadania domowego.')
			->html('<p>Pierwszy wlasny serwis z zadania domowego.</p>');

		$this->mailer->send($email);

		return 1;

	}

}