<?php

namespace App\Repository\Tools\QCM;

use App\Entity\Tools\QCM\QCMQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QCMQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method QCMQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method QCMQuestion[]    findAll()
 * @method QCMQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QCMQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QCMQuestion::class);
    }
}
