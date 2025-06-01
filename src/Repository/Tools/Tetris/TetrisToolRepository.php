<?php

namespace App\Repository\Tools\Tetris;

use App\Entity\Tools\Tetris\TetrisTool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TetrisTool|null find($id, $lockMode = null, $lockVersion = null)
 * @method TetrisTool|null findOneBy(array $criteria, array $orderBy = null)
 * @method TetrisTool[]    findAll()
 * @method TetrisTool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TetrisToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TetrisTool::class);
    }
}
