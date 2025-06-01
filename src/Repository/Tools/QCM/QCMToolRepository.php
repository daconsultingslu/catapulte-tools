<?php

namespace App\Repository\Tools\QCM;

use App\Entity\Tools\QCM\QCMTool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QCMTool|null find($id, $lockMode = null, $lockVersion = null)
 * @method QCMTool|null findOneBy(array $criteria, array $orderBy = null)
 * @method QCMTool[]    findAll()
 * @method QCMTool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QCMToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QCMTool::class);
    }
}
