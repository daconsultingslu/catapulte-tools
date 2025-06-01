<?php

namespace App\Repository\Tools\Trial;

use App\Entity\Tools\Trial\TrialTool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrialTool|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrialTool|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrialTool[]    findAll()
 * @method TrialTool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrialToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrialTool::class);
    }
}
