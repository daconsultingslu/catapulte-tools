<?php

namespace App\Form\ImportExport;

use App\Entity\ImportExport\ImportExport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ImportExportType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('file', FileType::class, array('label' => 'Fichier'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => ImportExport::class,
        ));
    }
}