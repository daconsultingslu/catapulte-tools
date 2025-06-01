<?php

namespace App\Repository\Tools\SelfEvaluation;

use App\Entity\Tools\SelfEvaluation\SelfEvaluationCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SelfEvaluationCriteria|null find($id, $lockMode = null, $lockVersion = null)
 * @method SelfEvaluationCriteria|null findOneBy(array $criteria, array $orderBy = null)
 * @method SelfEvaluationCriteria[]    findAll()
 * @method SelfEvaluationCriteria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SelfEvaluationCriteriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SelfEvaluationCriteria::class);
    }
}
