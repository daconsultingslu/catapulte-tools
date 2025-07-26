<?php

namespace App\Controller\Admin;

use App\Entity\Tools\Signature\SignatureTool;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SignatureToolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SignatureTool::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Émargements')
            ->setEntityLabelInSingular('Émargement');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextareaField::new('name')
                ->setRequired(true)
                ->setLabel('Nom'),
            TextField::new('hours')
                ->setRequired(true)
                ->setLabel('Horaires'),
            TextField::new('trainer')
                ->setRequired(true)
                ->setLabel('Formateur'),
            BooleanField::new('isMultiSignatures')
                ->setRequired(false)
                ->setLabel('Double signature ?'),
            BooleanField::new('isWithDischarge')
                ->setRequired(false)
                ->setLabel('Avec décharge ?'),
            AssociationField::new('event')
                ->setRequired(false)
                ->setLabel('Événement')
                ->setFormTypeOption('choice_label', function ($event) {
                    return $event->__toString();
                }),
        ];
    }
}
