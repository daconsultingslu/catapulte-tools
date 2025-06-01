<?php

namespace App\Repository;

use App\Entity\GroupEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Event;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupEvent[]    findAll()
 * @method GroupEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupEvent::class);
    }

    /**
     * @return GroupEvent[] Returns an array of Group objects
     */
    public function findAllByEvent(Event $event): array
    {
        return $this->createQueryBuilder('g')
          ->join('g.sessions', 's')
          ->join('s.event', 'e')
          ->andWhere('e.id = :val')
          ->setParameter('val', $event->getId())
          ->getQuery()
          ->getResult()
          ;
    }
}
