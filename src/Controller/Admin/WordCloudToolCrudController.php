<?php

namespace App\Controller\Admin;

use App\Entity\Tools\WordCloud\WordCloudTool;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WordCloudToolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WordCloudTool::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Nuages de mots')
            ->setEntityLabelInSingular('Nuage de mots');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextareaField::new('name')
                ->setRequired(true)
                ->setLabel('Nom'),
            TextField::new('baseline')
                ->setRequired(true)
                ->setLabel('Baseline'),
            AssociationField::new('event')
                ->setRequired(false)
                ->setLabel('Événement')
                ->setFormTypeOption('choice_label', function ($event) {
                    return $event->__toString();
                }),
        ];
    }
}
