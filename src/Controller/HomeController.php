<?php

namespace App\Controller;

use App\Message\SmsNotification;
use App\Service\AddHelper;
use App\Service\AddHelper2Decorator;
use App\Service\SendInfoToAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

	/**
	 * @Route("/", name="home_index")
	 */
	public function index(AddHelper $addHelper, MessageBusInterface $bus): Response
	{

		//dd($addHelper->add(2, 3));
		//zapisuje plik na dysku komputera. asynchronciznie
		//$bus->dispatch(new SmsNotification('Look! I created a message!', 2));
		return $this->render('home.html.twig');

	}



	/**
	 * @Route("/decorator", name="home_index_decorator")
	 */
	public function decorator(AddHelper2Decorator $addHelper2, MessageBusInterface $bus): Response
	{
		//testujemy dekoratory klas
		$result = $addHelper2->add(1,1);
		dd($result);

		return $this->render('home.html.twig');

	}



	/**
	 * @Route("/about/{firstName}", name="about_index")
	 */
	public function about(string $firstName = 'Me'): Response
	{
		$numbers = [
			'one',
			'two',
			'tree'
		];

		return $this->render('about.html.twig', [
			'firstName' => $firstName,
			'numbers' => $numbers
		]);

	}

	/**
	 * @Route("totolotek", name="totolotek_index")
	 */
	public function totolotek(): Response
	{
		$number = rand(1, 10);

		return $this->render('totolotek.html.twig', [
			'number' => $number,

		]);

	}

	/**
	 * @Route("/contact", name="contact_index")
	 */
	public function contact(SendInfoToAdmin $sendInfoToAdmin): Response
	{

		$sendInfoToAdmin->sendEmail();

		return $this->render('contact.html.twig');


	}
}