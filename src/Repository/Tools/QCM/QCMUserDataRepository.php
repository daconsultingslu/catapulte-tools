<?php

namespace App\Repository\Tools\QCM;

use App\Entity\Tools\QCM\QCMUserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\User\User;
use App\Entity\Session;
use App\Entity\Tools\QCM\QCMTool;
use App\Entity\Tools\QCM\QCMQuestion;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QCMUserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method QCMUserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method QCMUserData[]    findAll()
 * @method QCMUserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QCMUserDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QCMUserData::class);
    }

    /**
     * @return QCMUserData[] Returns an array of UserQCMAnswer objects
     */
    public function findByQCMQuestionByUser(QCMQuestion $qcmQuestion, User $user)
    {
        return $this->createQueryBuilder('qud')
          ->join('qud.userData', 'ud')
          ->join('ud.user', 'u')
          ->join('qud.qcmQuestion', 'qq')
          ->andWhere('u.id = :user')
          ->andWhere('qq.id = :qcmQuestion')
          ->setParameter('user', $user->getId())
          ->setParameter('qcmQuestion', $qcmQuestion->getId())
          ->getQuery()
          ->getResult()
        ;
    }

    /**
     * @return QCMUserData[] Returns an array of QCMUserData objects
     */
    public function findByQCMQuestionByUserId(QCMQuestion $qcmQuestion, $user)
    {
        return $this->createQueryBuilder('qud')
            ->join('qud.userData', 'ud')
            ->join('ud.user', 'u')
            ->join('qud.qcmQuestion', 'qq')
            ->andWhere('u.id = :user')
            ->andWhere('qq.id = :qcmQuestion')
            ->setParameter('user', $user)
            ->setParameter('qcmQuestion', $qcmQuestion->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return QCMUserData[] Returns an array of QCMUserData objects
     */
    public function getAverageTrueAnswerByQuestionBySession(?Session $session = null, ?QCMTool $tool = null, ?User $user = null)
    {
        $entityManager = $this->getEntityManager();

        $where = 'WHERE 1=1';
        $parameters = [];
        if ($session) {
            $where .= ' AND s = :session';
            $parameters['session'] = $session;
        }
        if ($user) {
            $where .= ' AND u = :user';
            $parameters['user'] = $user;
        }
        if ($tool) {
            $where .= ' AND qt = :tool';
            $parameters['tool'] = $tool;
        }

        $query01 = $entityManager->createQuery(
            'SELECT s.id as session, qq.id as question, count(qud.isRightAnswered) as nbAll
            FROM App\Entity\Tools\QCM\QCMQuestion qq
            LEFT JOIN qq.qcmUserDatas qud
            LEFT JOIN qq.qcmTool qt
            LEFT JOIN qud.userData ud
            LEFT JOIN ud.user u
            LEFT JOIN u.groupEvent ge
            LEFT JOIN ge.sessions s
            '.$where.'
            GROUP BY qq.id, s.id
            ORDER BY qq.id ASC'
        )->setParameters($parameters);

        $query02 = $entityManager->createQuery(
            'SELECT s.id as session, qq.id as question, count(qud.isRightAnswered) as nbTrue
            FROM App\Entity\Tools\QCM\QCMQuestion qq
            LEFT JOIN qq.qcmUserDatas qud WITH qud.isRightAnswered = TRUE
            LEFT JOIN qq.qcmTool qt
            LEFT JOIN qud.userData ud
            LEFT JOIN ud.user u
            LEFT JOIN u.groupEvent ge
            LEFT JOIN ge.sessions s
            '.$where.'
            GROUP BY qq.id, s.id
            ORDER BY qq.id ASC'
        )->setParameters($parameters);

        // returns an array of objects
        return [
            'all' => $query01->execute(),
            'true' => $query02->execute()
        ];
    }

    /**
     * @return QCMUserData[] Returns an array of QCMUserData objects
     */
    public function getScoreByUserBySession(Session $session = null, QCMTool $tool = null)
    {
        $entityManager = $this->getEntityManager();

        $where = 'WHERE 1=1';
        $parameters = [];
        if ($session) {
            $where .= ' AND s = :session';
            $parameters['session'] = $session;
        }
        if ($tool) {
            $where .= ' AND qt = :tool';
            $parameters['tool'] = $tool;
        }
        
        $query01 = $entityManager->createQuery(
          'SELECT u.firstname, u.lastname, SUM(CASE WHEN qud.isRightAnswered = 1 THEN 1 ELSE 0 END) as nbRight, sum(qud.time) as timeAnswer
            FROM App\Entity\Tools\QCM\QCMQuestion qq
            LEFT JOIN qq.qcmUserDatas qud
            LEFT JOIN qq.qcmTool qt
            LEFT JOIN qud.userData ud
            LEFT JOIN ud.user u
            LEFT JOIN u.groupEvent ge
            LEFT JOIN ge.sessions s
            '.$where.'
            GROUP BY u.id, s.id
            ORDER BY nbRight DESC, timeAnswer ASC'
        )->setParameters($parameters);

        // returns an array of objects
        return $query01->execute();
    }
}
