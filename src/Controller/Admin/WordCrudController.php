<?php

namespace App\Controller\Admin;

use App\Entity\Tools\Tetris\Word;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Word::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Mots')
            ->setEntityLabelInSingular('Mot');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name')
                ->setRequired(true)
                ->setLabel('Nom'),
            BooleanField::new('rightAnswer')
                ->setRequired(false)
                ->setLabel('Bonne réponse ?'),
            AssociationField::new('level')
                ->setRequired(true)
                ->setLabel('Niveau')
                ->setFormTypeOption('choice_label', function ($event) {
                    return $event->__toString();
                }),
        ];
    }
}
