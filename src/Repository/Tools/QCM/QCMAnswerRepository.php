<?php

namespace App\Repository\Tools\QCM;

use App\Entity\Tools\QCM\QCMAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QCMAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method QCMAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method QCMAnswer[]    findAll()
 * @method QCMAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QCMAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QCMAnswer::class);
    }
}
