<?php

namespace App\Repository;

use App\Entity\RegleDuree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegleDuree>
 *
 * @method RegleDuree|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegleDuree|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegleDuree[]    findAll()
 * @method RegleDuree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegleDureeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegleDuree::class);
    }

//    /**
//     * @return RegleDuree[] Returns an array of RegleDuree objects
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

//    public function findOneBySomeField($value): ?RegleDuree
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
