<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Blog;
use App\Entity\BlogCategory;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
	#[IsGranted('ROLE_ADMIN')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();
	    return $this->render('easy_admin/index.html.twig');

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
	        ->setTitle('<img src="assets/images/favicon/app_logo.png">  <span class="text-small">ToDo App :)</span>')

	        ->setTitle('ToDo App Admin :) ')

	        ->setFaviconPath('assets/images/favicon/favicon.ico')

	        //->renderContentMaximized()
	       // ->renderSidebarMinimized()
	        ;
    }

    public function configureMenuItems(): iterable
    {

        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Blog'),
            //MenuItem::linkToCrud('Categories', 'fa fa-tags', BlogCategory::class),
           // MenuItem::linkToCrud('Blog Posts', 'fa fa-file-text', Blog::class),

            MenuItem::section('Users'),
           // MenuItem::linkToCrud('Comments', 'fa fa-comment', Comment::class),
            //MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
        ];
    }
}
