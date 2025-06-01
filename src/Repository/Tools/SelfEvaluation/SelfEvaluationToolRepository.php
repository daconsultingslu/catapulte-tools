<?php

namespace App\Repository\Tools\SelfEvaluation;

use App\Entity\Tools\SelfEvaluation\SelfEvaluationTool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SelfEvaluationTool|null find($id, $lockMode = null, $lockVersion = null)
 * @method SelfEvaluationTool|null findOneBy(array $criteria, array $orderBy = null)
 * @method SelfEvaluationTool[]    findAll()
 * @method SelfEvaluationTool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SelfEvaluationToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SelfEvaluationTool::class);
    }
}
