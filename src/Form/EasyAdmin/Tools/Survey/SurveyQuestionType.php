<?php

namespace App\Form\EasyAdmin\Tools\Survey;

use App\Form\DataTransformer\Tools\Survey\SurveyToolToNumberTransformer;
use App\Entity\Tools\Survey\SurveyQuestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SurveyQuestionType extends AbstractType
{
    private $transformer;

    public function __construct(SurveyToolToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surveyTool', HiddenType::class)
            ->add('name', TextType::class, [
                'label' => 'Libellé de la question'
            ])
            ->add('canBeSkipped', CheckboxType::class, [
                'label' => 'Facultative ?',
                'required' => false
            ])
            ->add('usedInExportCalculation', CheckboxType::class, [
                'label' => 'Utilisée dans les calculs d\'export ?',
                'required' => false,
            ])
            ->add('surveyAnswers', CollectionType::class, [
                'entry_type' => SurveyAnswerType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'label' => 'Réponses'
            ])
        ;

        $builder->get('surveyTool')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SurveyQuestion::class,
        ]);
    }
}
