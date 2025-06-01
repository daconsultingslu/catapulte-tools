<?php

namespace App\Repository\Tools\Signature;

use App\Entity\Tools\Signature\SignatureTool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SignatureTool|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignatureTool|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignatureTool[]    findAll()
 * @method SignatureTool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignatureToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SignatureTool::class);
    }
}
