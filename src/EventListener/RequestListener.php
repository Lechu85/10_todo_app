<?php
namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class RequestListener
{
	public function onKernelRequest(RequestEvent $event)
	{
		// You get the exception object from the received event
		//dd($event);
	}
}
