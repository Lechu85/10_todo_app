<?php

namespace App\Service;

use App\Entity\User;
use Knp\Snappy\Pdf;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;
use Twig\Environment;

class SendingEmail
{
    private MailerInterface $mailer;
    private Environment $twig;
    private Pdf $pdf;
    private EntrypointLookupInterface $entrypointLookup;

    public function __construct(MailerInterface $mailer, Environment $twig, Pdf $pdf, EntrypointLookupInterface $entrypointLookup)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->pdf = $pdf;
        $this->entrypointLookup = $entrypointLookup;
    }

    public function sendWelcomeMessage(User $user): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            ->from(new Address('leszek@todoapp.pl', 'Leszek ToDo App :)'))
            ->to($user->getEmail())
            ->subject('Witamy w aplikacji ToDo App :)')
            ->htmlTemplate('email/welcome.html.twig')
            ->context([
                'user' => $user
            ]);

        $this->mailer->send($email);

        return $email;
    }

    public function sendUserDoneTaskDailyReportMessage(User $user, array $tasks): TemplatedEmail
    {

		//info to se email methods inside Twig functions double shift and pass WrappedTemplatedEmail

        //$html = $this->twig->render('email/user-daily-report-pdf.html.twig',[
        //    'tasks' => $tasks
       //]);

        //info mówi modułowi, żeby zapomniał, że coś renderował i za każdym wywołaniem pętli w twigu była cała tablica ze stylami.
        $this->entrypointLookup->reset();

        //info Metoda ta bierze treść html zapisuje w pliku tymczasowym
        // jeżeli wszystko pójdzie ok, to zmienna pdf będzie tekstem z zawartością poprawnym plikiem pdf.
        // Możemy z tym zrobić co chcemy. Np zapisac do pliku albo załączyc do emaila.
        //$pdf = $this->pdf->getOutputFromHtml($html);

        $email = (new TemplatedEmail())
            ->from(new Address('leszek@todoapp.pl', 'Leszek ToDo aPP'))
            ->to(new Address($user->getEmail(), $user->getName()))
            ->subject('Twój dzienny raport zamkniętych zadań')
            ->htmlTemplate('email/user-daily-report.html.twig')
            ->context([
                'user' => $user,
                'tasks' => $tasks
            ])
            //->attach($pdf, sprintf('daily-report-%s.pdf', date('Y-m-d')))
        ;
        //info jak mamy coś dużego, to możemy zrobić fopen() i podać (file handle) do pliku
	    //info NamedAddress() - jest starą metodą, już jej nie używamy.

        $this->mailer->send($email);

        return $email;

    }
}