<?php

namespace App\Repository;

use App\Entity\Parcel;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Parcel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parcel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parcel[]    findAll()
 * @method Parcel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParcelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parcel::class);
    }

    public function getNumberOfUnseenPerCategory(Category $category)
    {
        $cnt = $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->andWhere('a.category = :category')->setParameter('category', $category)
            ->andWhere('a.seen = false')
            ->getQuery()
            ->getSingleScalarResult()
        ;
        if (!$cnt) $cnt = 0;
        return (int)$cnt;
    }

    // /**
    //  * @return Parcel[] Returns an array of Parcel objects
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
    public function findOneBySomeField($value): ?Parcel
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
