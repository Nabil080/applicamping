<?php

namespace App\Repository;

use App\Entity\RegleSejour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegleSejour>
 *
 * @method RegleSejour|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegleSejour|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegleSejour[]    findAll()
 * @method RegleSejour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegleSejourRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegleSejour::class);
    }

    public function getCheckIns(): array
    {
        return $this->createQueryBuilder('regle')
            ->andWhere('regle.check_in IS NOT NULL')
            ->orderBy('regle.id', 'DESC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function getCheckOuts(): array
    {
        return $this->createQueryBuilder('regle')
            ->andWhere('regle.check_out IS NOT NULL')
            ->orderBy('regle.id', 'DESC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return RegleSejour[] Returns an array of RegleSejour objects
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

//    public function findOneBySomeField($value): ?RegleSejour
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
