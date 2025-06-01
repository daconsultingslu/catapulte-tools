<?php

namespace App\Form\EasyAdmin\Tools\QCM;

use App\Entity\Tools\QCM\QCMAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QCMAnswerType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Libellé de la réponse'
            ])
            ->add('rightAnswer', CheckboxType::class, [
                'label' => 'Bonne réponse ?',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QCMAnswer::class,
        ]);
    }
}
