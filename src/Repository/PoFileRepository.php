<?php

namespace App\Repository;

use App\Entity\PoFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PoFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method PoFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method PoFile[]    findAll()
 * @method PoFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PoFile::class);
    }

    // /**
    //  * @return PoFile[] Returns an array of PoFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PoFile
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
