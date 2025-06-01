<?php

namespace App\Repository\Tools\Survey;

use App\Entity\Tools\Survey\SurveyAnswer;
use App\Entity\Tools\Survey\SurveyTool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SurveyAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyAnswer[]    findAll()
 * @method SurveyAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyAnswer::class);
    }

    /**
     * @return SurveyAnswer[] Returns an array of SurveyAnswer objects
     */
    public function findDistinctAnswersByTool(SurveyTool $tool)
    {
        return $this->createQueryBuilder('sa')
            ->join('sa.surveyQuestion', 'sq')
            ->join('sq.surveyTool', 'st')
            ->andWhere('st.id = :tool')
            ->setParameter('tool', $tool->getId())
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
