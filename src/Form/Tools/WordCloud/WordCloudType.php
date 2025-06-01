<?php

namespace App\Form\Tools\WordCloud;

use App\Entity\Tools\WordCloud\WordCloudUserData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class WordCloudType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('word', TextType::class, array(
            'label' => false
          ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => WordCloudUserData::class,
        ));
    }
}
