<?php

namespace App\Repository\User;

use App\Entity\Session;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

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

    public function findUsersInactive()
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->where('u.isActive = :isActive')
            ->setParameter('isActive', false)
            ->setMaxResults(10)
            ->orderBy('u.id', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
}
