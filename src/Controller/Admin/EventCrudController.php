<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EventCrudController extends AbstractCrudController
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {}

    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Événements')
            ->setEntityLabelInSingular('Événement')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name')
                ->setRequired(true)
                ->setLabel('Nom de l\'événement')
                ->setTemplatePath('easy_admin/event_link.html.twig'),
            DateField ::new('startDate')
                ->setFormat('dd/MM/yyyy HH:mm')
                ->setRequired(true)
                ->setLabel('Date de début'),
            DateField ::new('endDate')
                ->setFormat('dd/MM/yyyy HH:mm')
                ->setRequired(true)
                ->setLabel('Date de fin'),
            AssociationField::new('brand')
                ->setRequired(true)
                ->setFormTypeOption('choice_label', 'name')
                ->setLabel('Marque'),
            TextField::new('language')
                ->setRequired(true)
                ->setLabel('Langue'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $accessEvent = Action::new('accessEvent', "Accéder à l'événement")
            ->linkToRoute('event_show', function (Event $event): array {
                return [
                    'event' => $event->getId(),
                ];
            })
            ->setHtmlAttributes(['target' => '_blank'])
        ;

        return $actions
            ->add(Crud::PAGE_INDEX, $accessEvent)
        ;
    }
}
