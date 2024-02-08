<?php

namespace App\Repository;

use App\Entity\Periode;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Periode>
 *
 * @method Periode|null find($id, $lockMode = null, $lockVersion = null)
 * @method Periode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Periode[]    findAll()
 * @method Periode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Periode::class);
    }

    public function findByStartEnd(DateTime $start, DateTime $end): ?Periode
    {
        return $this->createQueryBuilder('periode')
                    ->andWhere('periode.debut < :start')
                    ->setParameter('start', $start)
                    ->andWhere('periode.fin > :start')
                    ->setParameter('start', $end)
                    ->getQuery()
                    ->getOneOrNullResult();
    }
//    /**
//     * @return Periode[] Returns an array of Periode objects
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

//    public function findOneBySomeField($value): ?Periode
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
