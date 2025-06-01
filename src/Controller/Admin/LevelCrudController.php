<?php

namespace App\Controller\Admin;

use App\Entity\Tools\Tetris\Level;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LevelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Level::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Niveaux')
            ->setEntityLabelInSingular('Niveau');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name')
                ->setRequired(true)
                ->setLabel('Nom'),
            AssociationField::new('tetrisTool')
                ->setRequired(true)
                ->setLabel('Tetris')
                ->setFormTypeOption('choice_label', function ($event) {
                    return $event->__toString();
                }),
        ];
    }
}
