<?php

namespace App\Repository\Tools\Survey;

use App\Entity\Tools\Survey\SurveyTool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SurveyTool|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyTool|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyTool[]    findAll()
 * @method SurveyTool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyTool::class);
    }
}
