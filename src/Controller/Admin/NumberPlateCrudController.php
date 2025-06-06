<?php

namespace App\Controller\Admin;

use App\Entity\Tools\Trial\NumberPlate;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NumberPlateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return NumberPlate::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Véhicules')
            ->setEntityLabelInSingular('Véhicule');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('brand')
                ->setRequired(true)
                ->setLabel('Marque'),
            TextField::new('model')
                ->setRequired(true)
                ->setLabel('Modèle'),
            TextField::new('numberPlate')
                ->setRequired(true)
                ->setLabel('Plaque d\'immatriculation'),
            AssociationField::new('trialTool')
                ->setRequired(true)
                ->setLabel('Outil lié')
                ->setFormTypeOption('choice_label', function ($tool) {
                    return $tool->__toString();
                }),
        ];
    }
}
