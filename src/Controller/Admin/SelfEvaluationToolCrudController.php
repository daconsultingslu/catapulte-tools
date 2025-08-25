<?php

namespace App\Controller\Admin;

use App\Entity\Tools\SelfEvaluation\SelfEvaluationTool;
use App\Form\EasyAdmin\Tools\SelfEvaluation\SelfEvaluationCriteriaType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class SelfEvaluationToolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SelfEvaluationTool::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Auto-évaluations')
            ->setEntityLabelInSingular('Auto-évaluation')
            ->setDefaultSort(['id' => 'DESC']);
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
            CollectionField::new('selfEvaluationCriterias')
                ->setRequired(true)
                ->setLabel('Critères')
                ->setEntryType(SelfEvaluationCriteriaType::class)
                ->setFormTypeOptions([
                    'by_reference' => false, // Important pour que addItem() soit appelé
                    'allow_add' => true,     // Autoriser l'ajout d'éléments
                    'allow_delete' => true,  // Autoriser la suppression d'éléments
                    'entry_options' => [
                        'label' => false     // Cacher le label de chaque entrée
                    ],
                ]),
        ];
    }
}
