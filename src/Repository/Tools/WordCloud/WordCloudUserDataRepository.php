<?php

namespace App\Repository\Tools\WordCloud;

use App\Entity\GroupEvent;
use App\Entity\Tools\WordCloud\WordCloudUserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WordCloudUserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method WordCloudUserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method WordCloudUserData[]    findAll()
 * @method WordCloudUserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordCloudUserDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WordCloudUserData::class);
    }

    /**
     * @return string[] Returns an array of string
     */
    public function findAllByGroup(GroupEvent $group): array
    {
        $array = $this->createQueryBuilder('uwc')
          ->select('uwc.word')
          ->join('uwc.userData', 'ud')
          ->join('ud.user', 'u')
          ->join('u.groupEvent', 'ge')
          ->andWhere('ge.id = :val')
          ->setParameter('val', $group->getId())
          ->getQuery()
          ->getResult()
          ;

        $words = array();
        foreach($array as $w){
            $words[] = $w['word'];
        }

        return array_count_values($words);
    }
}
