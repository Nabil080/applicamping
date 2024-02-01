<?php

namespace App\Repository;

use App\Entity\OptionMaximum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OptionMaximum>
 *
 * @method OptionMaximum|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionMaximum|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionMaximum[]    findAll()
 * @method OptionMaximum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionMaximumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionMaximum::class);
    }

//    /**
//     * @return OptionMaximum[] Returns an array of OptionMaximum objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OptionMaximum
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
