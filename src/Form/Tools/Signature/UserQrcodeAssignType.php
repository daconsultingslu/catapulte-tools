<?php

namespace App\Form\Tools\Signature;

use App\Entity\Tools\Signature\Qrcode;
use App\Entity\User\User;
use App\Repository\QrcodeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserQrcodeAssignType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $session = $options['session'];
        $builder->add('qrcode', EntityType::class, [
            'class' => Qrcode::class,
            'query_builder' => function (QrcodeRepository $qrcodeRepository) use ($session) {
                return $qrcodeRepository->createQueryBuilder('q')
                    ->andWhere('q.user IS NULL')
                    ->andWhere('q.session = :session')
                    ->setParameter('session', $session->getId())
                    ->orderBy('q.id', 'ASC');
            },
            'choice_label' => 'displayedName'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => User::class,
          'session' => null
        ]);
    }
}