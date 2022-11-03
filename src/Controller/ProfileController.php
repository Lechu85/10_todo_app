<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

	#[Route('/edit_profile', name: 'app_edit_profile')]
	public function edit(): Response
	{

		$this->denyAccessUnlessGranted('ROLE_ADMIN');

		return $this->render('profile/index.html.twig', [
			'controller_name' => 'ProfileController',
		]);
	}
}
