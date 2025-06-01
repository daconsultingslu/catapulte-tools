<?php

namespace App\Controller\Admin;

use App\Entity\Session;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SessionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Session::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateField::new('startDate')
                ->setFormat('dd/MM/yyyy HH:mm')
                ->setRequired(true)
                ->setLabel('Date de début'),
            DateField::new('endDate')
                ->setFormat('dd/MM/yyyy HH:mm')
                ->setRequired(true)
                ->setLabel('Date de fin'),
            TextField::new('place')
                ->setRequired(true)
                ->setLabel('Lieu'),
            AssociationField::new('event')
                ->setRequired(true)
                ->setLabel('Événement'),
        ];
    }
}
