<?php

namespace App\Repository;

use App\Entity\TextVisualizator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TextVisualizator|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextVisualizator|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextVisualizator[]    findAll()
 * @method TextVisualizator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextVisualizatorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextVisualizator::class);
    }

    // /**
    //  * @return TextVisualizator[] Returns an array of TextVisualizator objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TextVisualizator
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
