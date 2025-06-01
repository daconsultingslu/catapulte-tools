<?php

namespace App\Repository\Tools\Trial;

use App\Entity\Event;
use App\Entity\Session;
use App\Entity\Tools\Trial\NumberPlate;
use App\Entity\Tools\Trial\TrialTool;
use App\Entity\Tools\Trial\TrialUserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrialUserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrialUserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrialUserData[]    findAll()
 * @method TrialUserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrialUserDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrialUserData::class);
    }

    public function insertTrialUserData($userId, $numberPlateId, $created)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            INSERT INTO trialUserData tud (tud.user_id, tud.number_plate_id)
            SELECT * FROM product p
            WHERE p.price > :price
            ORDER BY p.price ASC
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
          'price' => 1000
        ]);
    }

    public function myFindByDistinctUpdated(NumberPlate $numberPlate) {
        return $this->createQueryBuilder('tud')
          ->andWhere('tud.numberPlate = :val')
          ->setParameter('val', $numberPlate)
          ->groupBy('tud.updated')
          ->getQuery()
          ->getResult()
          ;
    }

    public function getByEvent(Event $event, TrialTool $tool) {
        return $this->createQueryBuilder('tud')
            ->join('tud.trialTool', 'tt')
            ->join('tt.event', 'e')
            ->andWhere('e.id = :event')
            ->andWhere('tt.id = :tool')
            ->setParameter('event', $event->getId())
            ->setParameter('tool', $tool->getId())
            ->getQuery()
            ->getResult()
          ;
    }

    public function findAllBySession(Session $session) {
        return $this->createQueryBuilder('tud')
            ->join('tud.trialTool', 'tt')
            ->join('tud.userData', 'ud')
            ->join('ud.user', 'u')
            ->join('u.groupEvent', 'ge')
            ->join('ge.sessions', 's')
            ->andWhere('s.id = :session')
            ->setParameter('session', $session->getId())
            ->getQuery()
            ->getResult()
          ;
    }

    public function findAllBySessionAndNumberPlate(Session $session, NumberPlate $numberPlate) {
        return $this->createQueryBuilder('tud')
            ->join('tud.trialTool', 'tt')
            ->join('tud.userData', 'ud')
            ->join('ud.user', 'u')
            ->join('u.groupEvent', 'ge')
            ->join('ge.sessions', 's')
            ->andWhere('s.id = :session')
            ->andWhere('tud.numberPlate = :numberPlate')
            ->setParameter('session', $session->getId())
            ->setParameter('numberPlate', $numberPlate->getId())
            ->orderBy('tud.created','DESC')
            ->getQuery()
            ->getResult()
          ;
    }
}
