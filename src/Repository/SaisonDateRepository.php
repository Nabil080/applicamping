<?php

namespace App\Repository;

use App\Entity\SaisonDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SaisonDate>
 *
 * @method SaisonDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaisonDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaisonDate[]    findAll()
 * @method SaisonDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaisonDateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaisonDate::class);
    }

//    /**
//     * @return SaisonDate[] Returns an array of SaisonDate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SaisonDate
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
