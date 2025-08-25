<?php

namespace App\Controller\Admin;

use App\Entity\GroupEvent;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GroupEventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GroupEvent::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Groupes')
            ->setEntityLabelInSingular('Groupe')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name')
                ->setRequired(true)
                ->setLabel('Nom du groupe'),
            AssociationField::new('sessions')
                ->setRequired(true)
                ->setLabel('Sessions')
                ->setFormTypeOption('choice_label', function ($session) {
                    return $session->__toString();
                })
                ->setFormTypeOption('multiple', true),
        ];
    }
}
