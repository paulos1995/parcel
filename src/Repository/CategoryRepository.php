<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }


    public function findByFragment($fragment)
    {
        $results1 = $this->createQueryBuilder('o')
            ->andWhere('o.name LIKE :val')
            ->setParameter('val', $fragment.'%')
            ->orderBy('o.name', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
        $results2 = $this->createQueryBuilder('o')
            ->andWhere('o.name LIKE :val')
            ->setParameter('val', '%'.$fragment.'%')
            ->orderBy('o.name', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
        foreach ($results1 as $one) {
            /**
             * @var Category $one
             */
            $ids1[$one->getId()] = $one->getId();
        }
        $megedResults = $results1;
        foreach ($results2 as $one) {
            if (!isset($ids1[$one->getId()])) $megedResults[]=$one;
        }
        return $megedResults;
    }


    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
