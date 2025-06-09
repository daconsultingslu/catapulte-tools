<?php

namespace App\Form\Tools\QCM;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Tools\QCM\QCMAnswer;
use App\Entity\Tools\QCM\QCMUserData;
use App\Repository\Tools\QCM\QCMAnswerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class QCMType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $qcmQuestion = $options['qcmQuestion'];

        $builder
          ->add('qcmQuestion', HiddenType::class)
          ->add('qcmAnswers', EntityType::class, [
              'class' => QCMAnswer::class,
              'choice_label' => 'name',
              'multiple' => true,
              'expanded' => true,
              'by_reference' => false,
              'query_builder' => function (QCMAnswerRepository $qar) use ($qcmQuestion) {
                  return $qar
                    ->createQueryBuilder('qa')
                    ->join('qa.qcmQuestion', 'qq')
                    ->andWhere('qq.id = :val')
                    ->setParameter('val', $qcmQuestion->getId())
                  ;
              },
              'required' => true,
              'label' => false,
          ])
          ->add('time', HiddenType::class)
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => QCMUserData::class,
        ));

        $resolver->setRequired('qcmQuestion');
    }
}
