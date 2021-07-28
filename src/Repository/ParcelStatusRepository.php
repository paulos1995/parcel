<?php

namespace App\Repository;

use App\Entity\ParcelStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParcelStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParcelStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParcelStatus[]    findAll()
 * @method ParcelStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParcelStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParcelStatus::class);
    }

    // /**
    //  * @return ParcelStatus[] Returns an array of ParcelStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParcelStatus
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
