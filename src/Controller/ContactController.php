<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {

	    $form = $this->createForm(ContactType::class);

	    $form->handleRequest($request);
	    if ($form->isSubmitted() && $form->isValid()) {
		    // $form->getData() holds the submitted values
		    // but, the original `$task` variable has also been updated
		    $contact = $form->getData();
			//dd($contact['name']);

		    $email = (new Email())
			    ->from($contact['email'])
			    ->to('admin@sotech.pl')
			    ->subject('Email z dzialu kontakt')
			    ->text('Autor: '.$contact['name'].'<br><br>'.$contact['email'].'<br><br>'.$contact['content'])
			    ->html('<p><b>Autor:</b> '.$contact['name'].'<br><br><b>Email: </b>'.$contact['email'].'<br><br>'.$contact['content'].'</p>');

		    $mailer->send($email);

			$this->addFlash('success', 'Wysłano email do obsługi systemu');

		    return $this->redirectToRoute('app_contact');


	    }


	    return $this->renderForm('contact/index.html.twig', [
		    'form' => $form,
	    ]);

    }
}
