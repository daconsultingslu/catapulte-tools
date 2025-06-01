<?php

namespace App\Repository\Tools\Signature;

use App\Entity\Tools\Signature\SignatureTool;
use App\Entity\Tools\Signature\SignatureUserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SignatureUserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignatureUserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignatureUserData[]    findAll()
 * @method SignatureUserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignatureUserDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SignatureUserData::class);
    }

    public function myFindBySignatureTool(SignatureTool $tool) {
        return $this->createQueryBuilder('ust')
          ->join('ust.signatureTool', 'st')
          ->join('ust.userData', 'ud')
          ->join('ud.user', 'u')
          ->andWhere('st.id = :val')
          ->setParameter('val', $tool->getId())
          ->addOrderBy('ust.session', 'ASC')
          ->addOrderBy('u.lastname', 'ASC')
          ->addOrderBy('u.firstname', 'ASC')
          ->getQuery()
          ->getResult()
          ;
    }
}
