<?php

namespace App\Form\Tools\Survey;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Tools\Survey\SurveyAnswer;
use App\Entity\Tools\Survey\SurveyUserData;


class SurveyType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $options['entity_manager'];
        $question = $options['question'];

        $builder
          ->add('surveyAnswer', ChoiceType::class, array(
            "choices" => $this->fillAnswers($entityManager, $question),
              'expanded' => true,
              'choice_label' => 'name',
              'label' => false
          ))
          ->add('comment', TextareaType::class, array(
            'label' => 'Commentaires',
              'required' => false
          ))
        ;

    }

    private function fillAnswers($entityManager, $question) {

        $answersRepo = $entityManager->getRepository(SurveyAnswer::class);

        return $answersRepo->createQueryBuilder('sa')
              ->join('sa.surveyQuestion', 'sq')
              ->andWhere('sq.id = :val')
              ->setParameter('val', $question->getId())
              ->orderBy('sa.value', 'DESC')
              ->getQuery()
              ->getResult()
          ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => SurveyUserData::class,
        ));

        $resolver->setRequired('entity_manager');
        $resolver->setRequired('question');
    }
}