<?php

namespace App\Controller\Admin;

use App\Entity\Theme;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ThemeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Theme::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Thèmes')
            ->setEntityLabelInSingular('Thème');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name')
                ->setRequired(true)
                ->setLabel('Nom du thème'),
            ImageField::new('logo')
                ->setBasePath('uploads/theme')
                ->setUploadDir('public/uploads/theme')
                ->setRequired(false)
                ->setLabel('Logo'),
            TextField::new('backgroundColor')
                ->setRequired(false)
                ->setLabel('Couleur de fond'),
            AssociationField::new('brand')
                ->setRequired(false)
                ->setFormTypeOption('choice_label', 'name')
                ->setLabel('Marque'),
        ];
    }
}
