<?php

namespace App\Controller\Admin;

use App\Entity\Tools\QCM\QCMTool;
use App\Form\EasyAdmin\Tools\QCM\QCMQuestionType;
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

class QCMToolCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly UrlGeneratorInterface $urlGenerator,
    )
    {}

    public static function getEntityFqcn(): string
    {
        return QCMTool::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('QCM')
            ->setEntityLabelInSingular('QCM');
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
            CollectionField::new('qcmQuestions')
                ->setRequired(true)
                ->setLabel('Questions')
                ->setEntryType(QCMQuestionType::class)
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true)
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $duplicateQCM = Action::new('duplicateQCM', 'Dupliquer')
            ->setIcon('fa fa-copy')
            ->linkToUrl(function (QCMTool $qcmTool) {
                return $this->urlGenerator->generate('duplicate_qcm_tool', ['id' => $qcmTool->getId()]);
            });

        return $actions
            ->add(Crud::PAGE_INDEX, $duplicateQCM);
    }

    #[Route('/admin/qcm_tool/{id}/duplicate', name: 'duplicate_qcm_tool')]
    public function duplicateQCMTool(QCMTool $qcmTool): Response
    {
        $newQCMTool = clone $qcmTool;
        $newQCMTool->setName($qcmTool->getName() . ' (Copie)');
        $this->entityManager->persist($newQCMTool);
        $this->entityManager->flush();

        $this->addFlash('success', 'QCM dupliqué avec succès.');

        $url = $this->adminUrlGenerator
            ->setController(QCMToolCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }
}
