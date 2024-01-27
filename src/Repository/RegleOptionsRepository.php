<?php

namespace App\Repository;

use App\Entity\RegleOptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegleOptions>
 *
 * @method RegleOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegleOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegleOptions[]    findAll()
 * @method RegleOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegleOptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegleOptions::class);
    }

//    /**
//     * @return RegleOptions[] Returns an array of RegleOptions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RegleOptions
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
