<?php

namespace App\Controller\Admin;

use App\Entity\Tools\Tetris\TetrisTool;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class TetrisToolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TetrisTool::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Tetris')
            ->setEntityLabelInSingular('Tetris');
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
        ];
    }
}
