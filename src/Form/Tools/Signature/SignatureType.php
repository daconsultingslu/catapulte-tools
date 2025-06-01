<?php

namespace App\Form\Tools\Signature;

use App\Entity\Tools\Signature\SignatureUserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SignatureType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('signature', HiddenType::class, array(
            'required' => false
          ))
          ->add('isOff', CheckboxType::class, array(
            'label'    => 'Absent ?',
            'required' => false,
          ))
          ->add('reason', ChoiceType::class, array(
            'choices'  => array(
              'Absent' => 'Absent',
              'Changement de date' => 'Changement de date',
              'Maladie' => 'Maladie'
            ),
              'required' => false,
            'label' => 'Raison'
          ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => SignatureUserData::class,
        ));
    }
}