<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

	#[Route('/admin_comments', name: 'app_admin_comments')]
	public function comments(): Response
	{
		//używajmy tej funkcji do sprawdzania roli 
		$this->denyAccessUnlessGranted('ROLE_COMMENTS_MODERATOR');

		return $this->render('admin/comments.html.twig', [
			'controller_name' => 'AdminController',
		]);
	}
}
