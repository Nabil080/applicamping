<?php

namespace App\Repository;

use App\Entity\RegleReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegleReservation>
 *
 * @method RegleReservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegleReservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegleReservation[]    findAll()
 * @method RegleReservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegleReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegleReservation::class);
    }

//    /**
//     * @return RegleReservation[] Returns an array of RegleReservation objects
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

//    public function findOneBySomeField($value): ?RegleReservation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
