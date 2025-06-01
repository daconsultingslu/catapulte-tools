<?php

namespace App\Repository\Tools\SelfEvaluation;

use App\Entity\Tools\SelfEvaluation\SelfEvaluationCriteria;
use App\Entity\Tools\SelfEvaluation\SelfEvaluationUserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\GroupEvent;
use App\Entity\User\User;
use App\Entity\Tools\SelfEvaluation\SelfEvaluationTool;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SelfEvaluationUserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method SelfEvaluationUserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method SelfEvaluationUserData[]    findAll()
 * @method SelfEvaluationUserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SelfEvaluationUserDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SelfEvaluationUserData::class);
    }

    /**
     * @return Criteria[] Returns an array of Criteria objects
     */
    public function findAllByStepByGroupByTool($step, GroupEvent $group, SelfEvaluationTool $tool)
    {
        return $this->createQueryBuilder('seud')
            ->select('avg(seud.note) as note_avg, sec.name')
            ->join('seud.selfEvaluationCriteria', 'sec')
            ->join('seud.userData', 'ud')
            ->join('ud.user', 'u')
            ->join('u.groupEvent', 'ge')
            ->join('sec.selfEvaluationTool', 'setool')
            ->andWhere('ge.id = :val')
            ->andWhere('seud.step = :step')
            ->andWhere('setool.id = :tool')
            ->setParameter('val', $group->getId())
            ->setParameter('step', $step)
            ->setParameter('tool', $tool->getId())
            ->groupBy('sec.id')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCriteriaByUser(SelfEvaluationCriteria $criteria, User $user, $step)
    {
        return $this->createQueryBuilder('seud')
          ->join('seud.userData', 'ud')
          ->join('ud.user', 'u')
          ->join('seud.selfEvaluationCriteria', 'sec')
          ->andWhere('u.id = :user')
          ->andWhere('sec.id = :criteria')
          ->andWhere('seud.step = :step')
          ->setParameter('user', $user->getId())
          ->setParameter('criteria', $criteria->getId())
          ->setParameter('step', $step)
          ->getQuery()
          ->getResult()
          ;
    }
}
