<?php

namespace App\Form\EasyAdmin\Tools\QCM;

use App\Form\DataTransformer\Tools\QCM\QCMToolToNumberTransformer;
use App\Entity\Tools\QCM\QCMQuestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class QCMQuestionType extends AbstractType
{
    private $transformer;

    public function __construct(QCMToolToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('qcmTool', HiddenType::class)
            ->add('name', TextType::class, [
                'label' => 'Libellé de la question'
            ])
            ->add('canBeSkipped', CheckboxType::class, [
                'label' => 'Facultative ?',
                'required' => false,
            ])
            ->add('qcmAnswers', CollectionType::class, [
                'entry_type' => QCMAnswerType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'label' => 'Réponses',
                'error_bubbling' => false,
            ])
        ;

        $builder->get('qcmTool')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QCMQuestion::class,
        ]);
    }
}
