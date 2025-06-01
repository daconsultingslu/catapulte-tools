<?php

namespace App\Repository\Tools\Tetris;

use App\Entity\GroupEvent;
use App\Entity\Tools\Tetris\TetrisUserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TetrisUserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method TetrisUserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method TetrisUserData[]    findAll()
 * @method TetrisUserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TetrisUserDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TetrisUserData::class);
    }

    public function findAllByGroup(GroupEvent $group) {
        return $this->createQueryBuilder('utt')
            ->join('utt.userData', 'ud')
            ->join('ud.user', 'u')
            ->join('u.groupEvent', 'ge')
            ->andWhere('ge.id = :group')
            ->setParameter('group', $group->getId())
            ->getQuery()
            ->getResult()
          ;
    }
}
