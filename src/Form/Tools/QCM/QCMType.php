<?php
/**
 * Created by PhpStorm.
 * User: damienaulombard
 * Date: 03/06/2018
 * Time: 14:35
 */

namespace App\Form\Tools\QCM;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Tools\QCM\QCMAnswer;
use App\Entity\Tools\QCM\QCMUserData;


class QCMType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $options['entity_manager'];
        $qcmQuestion = $options['qcmQuestion'];

        $builder
          ->add('qcmQuestion', HiddenType::class)
          ->add('qcmAnswers', ChoiceType::class, array(
            "choices" => $this->fillAnswers($entityManager, $qcmQuestion),
              'expanded' => true,
              'multiple' => count($qcmQuestion->getTrueQCMAnswers()) !== 1,
              'choice_label' => 'name',
              'label' => false,
              'mapped' => false,
              'choice_value' => 'id',
              'required' => true,
          ))
          ->add('time', HiddenType::class)
        ;

    }

    private function fillAnswers($entityManager, $qcmQuestion) {
        $qcmAnswersRepo = $entityManager->getRepository(QCMAnswer::class);

        return $qcmAnswersRepo->createQueryBuilder('qa')
              ->join('qa.qcmQuestion', 'qq')
              ->andWhere('qq.id = :val')
              ->setParameter('val', $qcmQuestion->getId())
              ->getQuery()
              ->getResult()
          ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => QCMUserData::class,
        ));

        $resolver->setRequired('entity_manager');
        $resolver->setRequired('qcmQuestion');
    }
}
