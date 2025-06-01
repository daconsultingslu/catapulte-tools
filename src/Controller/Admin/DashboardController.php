<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Entity\Event;
use App\Entity\GroupEvent;
use App\Entity\Session;
use App\Entity\Theme;
use App\Entity\Tools\QCM\QCMTool;
use App\Entity\Tools\SelfEvaluation\SelfEvaluationTool;
use App\Entity\Tools\Signature\SignatureTool;
use App\Entity\Tools\Survey\SurveyTool;
use App\Entity\Tools\Tetris\Level;
use App\Entity\Tools\Tetris\TetrisTool;
use App\Entity\Tools\Tetris\Word;
use App\Entity\Tools\Trial\NumberPlate;
use App\Entity\Tools\Trial\TrialTool;
use App\Entity\Tools\WordCloud\WordCloudTool;
use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->redirectToRoute('admin_event_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Catapulte')
            ->setTranslationDomain('admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Theming');
        yield MenuItem::linkToCrud('Marques', 'fa fa-copyright', Brand::class);
        yield MenuItem::linkToCrud('Thèmes', 'fa fa-pencil', Theme::class);

        yield MenuItem::section('Événements');
        yield MenuItem::linkToCrud('Événements', 'fa fa-calendar', Event::class);
        yield MenuItem::linkToCrud('Sessions', 'fa fa-calendar-plus', Session::class);
        yield MenuItem::linkToCrud('Groupes', 'fa fa-user-group', GroupEvent::class);

        yield MenuItem::section('Outils');
        yield MenuItem::subMenu('Émargement', '')->setSubItems([
            MenuItem::linkToCrud('Émargement', 'fa fa-signature', SignatureTool::class),
        ]);

        yield MenuItem::subMenu('Essais', '')->setSubItems([
            MenuItem::linkToCrud('Essais', 'fa fa-id-card', TrialTool::class),
            MenuItem::linkToCrud('Véhicules', 'fa fa-car', NumberPlate::class),
        ]);

        yield MenuItem::subMenu('Enquête de satisfaction', '')->setSubItems([
            MenuItem::linkToCrud('Enquête de satisfaction', 'fa fa-face-smile', SurveyTool::class),
        ]);

        yield MenuItem::subMenu('Nuage de mots', '')->setSubItems([
            MenuItem::linkToCrud('Nuage de mots', 'fa fa-cloud', WordCloudTool::class),
        ]);

        yield MenuItem::subMenu('Auto-évaluation', '')->setSubItems([
            MenuItem::linkToCrud('Auto-évaluation', 'fa fa-sheet-plastic', SelfEvaluationTool::class),
        ]);

        yield MenuItem::subMenu('QCM', '')->setSubItems([
            MenuItem::linkToCrud('QCM', 'fa fa-question', QCMTool::class),
        ]);

        yield MenuItem::subMenu('Tetris', '')->setSubItems([
            MenuItem::linkToCrud('Tetris', 'fa fa-gamepad', TetrisTool::class),
            MenuItem::linkToCrud('Niveau', 'fa fa-turn-up', Level::class),
            MenuItem::linkToCrud('Mot', 'fa fa-file-word', Word::class),
        ]);

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
    }
}
