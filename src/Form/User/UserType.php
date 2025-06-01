<?php

declare(strict_types=1);

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\GroupEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\GroupEventRepository;

final class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', TextType::class, [
          'constraints' => [new NotBlank()],
          'label' => 'Prénom'
        ]);
        $builder->add('lastname', TextType::class, [
          'constraints' => [new NotBlank()],
          'label' => 'Nom'
        ]);
        $builder->add('concessionCode', TextType::class, [
          'constraints' => [new NotBlank()],
          'label' => 'Code concession'
        ]);
        $builder->add('details', TextType::class, [
          'constraints' => [new NotBlank()],
          'label' => 'Détails'
        ]);
        $builder->add('groupEvent', EntityType::class, array(
          'class' => GroupEvent::class,
          'choice_label' => 'name',
          'label' => 'Groupe',
          'query_builder' => function (GroupEventRepository $er) use ($options) {
              return $er->createQueryBuilder('g')
                ->join('g.sessions', 's')
                ->andWhere('s.id = :val')
                ->setParameter('val', $options['session']->getId());
          },
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'session' => null,
        ));
    }
}
