<?php

namespace App\Form\EasyAdmin\Tools\SelfEvaluation;

use App\Entity\Tools\SelfEvaluation\SelfEvaluationCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SelfEvaluationCriteriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Libellé du critère'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SelfEvaluationCriteria::class,
        ]);
    }
}
