<?php

namespace App\Form\EasyAdmin\Tools\Survey;

use App\Entity\Tools\Survey\SurveyAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurveyAnswerType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Libellé de la réponse'
            ])
            ->add('value', NumberType::class, [
                'label' => 'Valeur (nombre entier)'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SurveyAnswer::class,
        ]);
    }
}
