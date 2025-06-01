<?php

namespace App\Repository\Tools\Trial;

use App\Entity\Tools\Trial\NumberPlate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NumberPlate|null find($id, $lockMode = null, $lockVersion = null)
 * @method NumberPlate|null findOneBy(array $criteria, array $orderBy = null)
 * @method NumberPlate[]    findAll()
 * @method NumberPlate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NumberPlateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NumberPlate::class);
    }
}
