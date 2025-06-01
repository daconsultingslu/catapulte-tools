<?php

namespace App\Repository\User;

use App\Entity\User\User;
use App\Entity\Session;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findAllBySession(Session $session, $returnArray = false): array
    {
        $query = $this->createQueryBuilder('u')
            ->join('u.groupEvent', 'ge')
            ->join('ge.sessions', 's')
            ->andWhere('s.id = :val')
            ->setParameter('val', $session->getId())
            ->orderBy('u.lastname', 'ASC')
            ->getQuery();

        if($returnArray){
            return $query->getArrayResult();
        }

        return $query->getResult();
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findAllByEvent(Event $event): array
    {
        return $this->createQueryBuilder('u')
          ->join('u.groupEvent', 'ge')
          ->join('ge.sessions', 's')
          ->join('s.event', 'e')
          ->andWhere('e.id = :val')
          ->setParameter('val', $event->getId())
          ->orderBy('u.lastname', 'ASC')
          ->groupBy('u.id')
          ->getQuery()
          ->getArrayResult()
          ;
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function myFindByToken($token): array
    {
        return $this->createQueryBuilder('u')
          ->where('u.token = :val')
          ->setParameter('val', $token)
          ->getQuery()
          ->getResult()
          ;
    }
}
