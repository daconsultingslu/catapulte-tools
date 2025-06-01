<?php

namespace App\Controller\Admin;

use App\Entity\Tools\Survey\SurveyTool;
use App\Form\EasyAdmin\Tools\Survey\SurveyQuestionType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SurveyToolCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly UrlGeneratorInterface $urlGenerator,
    )
    {}

    public static function getEntityFqcn(): string
    {
        return SurveyTool::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Enquêtes de satisfaction')
            ->setEntityLabelInSingular('Enquête de satisfaction');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextareaField::new('name')
                ->setRequired(true)
                ->setLabel('Nom'),
            AssociationField::new('event')
                ->setRequired(false)
                ->setLabel('Événement')
                ->setFormTypeOption('choice_label', function ($event) {
                    return $event->__toString();
                }),
            CollectionField::new('surveyQuestions')
                ->setRequired(true)
                ->setLabel('Questions')
                ->setEntryType(SurveyQuestionType::class)
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true)
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicateSurvey = Action::new('duplicateSurvey', 'Dupliquer')
            ->setIcon('fa fa-copy')
            ->linkToUrl(function (SurveyTool $surveyTool) {
                return $this->urlGenerator->generate('duplicate_survey_tool', ['id' => $surveyTool->getId()]);
            });

        return $actions
            ->add(Crud::PAGE_INDEX, $duplicateSurvey);
    }

    #[Route('/admin/survey_tool/{id}/duplicate', name: 'duplicate_survey_tool')]
    public function duplicateQCMTool(SurveyTool $surveyTool): Response
    {
        $newSurveyTool = clone $surveyTool;
        $newSurveyTool->setName($surveyTool->getName() . ' (Copie)');
        $this->entityManager->persist($newSurveyTool);
        $this->entityManager->flush();

        $this->addFlash('success', 'Enquête de satisfaction dupliquée avec succès.');

        $url = $this->adminUrlGenerator
            ->setController(SurveyToolCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }
}
