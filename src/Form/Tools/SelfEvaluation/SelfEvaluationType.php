<?php
/**
 * Created by PhpStorm.
 * User: damienaulombard
 * Date: 03/06/2018
 * Time: 14:35
 */

namespace App\Form\Tools\SelfEvaluation;

use App\Entity\Tools\SelfEvaluation\SelfEvaluationUserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;


class SelfEvaluationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('note', RangeType::class, array(
            'attr' => array(
              'min' => 0,
              'max' => 10,
              'value' => 5
            ),
              'label' => false
          ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => SelfEvaluationUserData::class,
        ));
    }
}