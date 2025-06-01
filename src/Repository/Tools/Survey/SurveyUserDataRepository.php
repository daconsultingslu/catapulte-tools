<?php

namespace App\Repository\Tools\Survey;

use App\Entity\Tools\Survey\SurveyUserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\User\User;
use App\Entity\Tools\Survey\SurveyQuestion;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SurveyUserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method SurveyUserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method SurveyUserData[]    findAll()
 * @method SurveyUserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SurveyUserDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyUserData::class);
    }

    /**
     * @return SurveyUserData[] Returns an array of SurveyUserData objects
     */
    public function findByQuestionByUser(SurveyQuestion $question, User $user)
    {
        return $this->createQueryBuilder('sua')
          ->join('sua.userData', 'ud')
          ->join('ud.user', 'u')
          ->join('sua.surveyAnswer', 'sa')
          ->join('sa.surveyQuestion', 'sq')
          ->andWhere('u.id = :user')
          ->andWhere('sq.id = :question')
          ->setParameter('user', $user->getId())
          ->setParameter('question', $question->getId())
          ->getQuery()
          ->getResult()
          ;
    }

    /**
     * @return SurveyUserData[] Returns an array of SurveyUserData objects
     */
    public function findQuestionIsSkippedByUser(SurveyQuestion $question, User $user)
    {
        return $this->createQueryBuilder('sua')
          ->join('sua.userData', 'ud')
          ->join('ud.user', 'u')
          ->join('sua.surveyQuestion', 'sq')
          ->andWhere('u.id = :user')
          ->andWhere('sq.id = :question')
          ->setParameter('user', $user->getId())
          ->setParameter('question', $question->getId())
          ->getQuery()
          ->getResult()
          ;
    }

    /**
     * @return SurveyUserData[] Returns an array of SurveyUserData objects
     */
    public function findByQuestionByUserWithIds($question, $user)
    {
        return $this->createQueryBuilder('sua')
          ->select('sa.name, sua.comment', 'sa.value')
          ->join('sua.userData', 'ud')
          ->join('ud.user', 'u')
          ->join('sua.surveyAnswer', 'sa')
          ->join('sa.surveyQuestion', 'sq')
          ->andWhere('u.id = :user')
          ->andWhere('sq.id = :question')
          ->setParameter('user', $user)
          ->setParameter('question', $question)
          ->getQuery()
          ->getArrayResult()
          ;
    }

    /**
     * @return SurveyUserData[] Returns an array of SurveyUserData objects
     */
    public function findQuestionIsSkippedByUserWithIds($question, $user)
    {
        return $this->createQueryBuilder('sua')
          ->join('sua.userData', 'ud')
          ->join('ud.user', 'u')
          ->join('sua.surveyQuestion', 'sq')
          ->andWhere('u.id = :user')
          ->andWhere('sq.id = :question')
          ->setParameter('user', $user)
          ->setParameter('question', $question)
          ->getQuery()
          ->getArrayResult()
          ;
    }

    public function findQuestionsWithCountAnswers($session) {
        return $this->createQueryBuilder('sua')
          ->select(
            'sq.id as questionId',
            'sq.name as questionName',
            'COUNT(sua.id) as nbAnswers',
            'sa.value as answerValue',
            'sq.usedInExportCalculation as calc'
          )

          ->join('sua.userData', 'ud')
          ->join('ud.user', 'u')
          ->join('u.groupEvent', 'ge')
          ->join('ge.sessions', 's')
          ->join('sua.surveyAnswer', 'sa')
          ->join('sa.surveyQuestion', 'sq')

          ->andWhere('s.id = :session')
          ->setParameter('session', $session->getId())
          ->groupBy('sua.surveyQuestion')
          ->addGroupBy('sua.surveyAnswer')
          ->orderBy('sq.id', 'ASC')
          ->getQuery()
          ->getArrayResult()
        ;
    }
}
