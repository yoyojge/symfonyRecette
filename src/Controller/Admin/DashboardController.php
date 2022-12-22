<?php

namespace App\Controller\Admin;


use App\Entity\Note;
use App\Entity\User;
use App\Entity\Recette;
use App\Entity\Commentaire;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\RecetteCrudController;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
            
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(RecetteCrudController::class)->generateUrl());

        
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mon admin Recette');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('mon Dashboard a moa', 'fa fa-home');
        yield MenuItem::linkToCrud('Mes Recettes', 'fa-solid fa-utensils', Recette::class);
        yield MenuItem::linkToCrud('Les commentaires', 'fa-solid fa-comment', Commentaire::class);
        yield MenuItem::linkToCrud('Les notes', 'fas fa-list', Note::class);
        yield MenuItem::linkToCrud('Les users', 'fa-solid fa-users', User::class);
    }
}
