<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function findEventByUser(User $user)
    {
        return $this->createQueryBuilder('e')
            ->join('e.sessions', 's')
            ->join('s.groupEvents', 'ge')
            ->join('ge.users', 'u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $user->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function findAllOlderThanDate(\DateTime $dateTime)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.startDate < :date')
            ->setParameter('date', $dateTime->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;
    }
}
