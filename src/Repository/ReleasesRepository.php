<?php

namespace App\Repository;

use App\Entity\Releases;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Releases|null find($id, $lockMode = null, $lockVersion = null)
 * @method Releases|null findOneBy(array $criteria, array $orderBy = null)
 * @method Releases[]    findAll()
 * @method Releases[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReleasesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Releases::class);
    }

    // /**
    //  * @return Releases[] Returns an array of Releases objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Releases
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
