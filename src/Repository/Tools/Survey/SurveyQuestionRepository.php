<?php

namespace App\Repository\Tools\Survey;

use App\Entity\Tools\Survey\SurveyQuestion;
use App\Entity\Event;
use App\Entity\Tools\Survey\SurveyTool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SurveyQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyQuestion[]    findAll()
 * @method SurveyQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyQuestion::class);
    }

    /**
     * @return SurveyQuestion[] Returns an array of SurveyQuestion objects
     */
    public function findAllByEvent(Event $event, SurveyTool $tool)
    {
        return $this->createQueryBuilder('sq')
            ->join('sq.surveyTool', 'sst')
            ->join('sst.event', 'e')
            ->andWhere('e.id = :event')
            ->andWhere('sst.id = :tool')
            ->setParameter('event', $event->getId())
            ->setParameter('tool', $tool->getId())
            ->orderBy('sq.id', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
