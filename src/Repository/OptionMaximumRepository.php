<?php

namespace App\Repository;

use App\Entity\OptionMaximum;
use App\Entity\Reservation;
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


    public function findByReservation(Reservation $reservation): array
    {
        dd($this->createQueryBuilder('om')
        ->select('om.id')
        ->leftJoin('option_maximum_hebergement','omh','on', 'om.id = omh.option_maximum_id')
        ->leftJoin('option_maximum_saison', 'oms', 'on', 'om.id = oms.option_maximumm_id')
        ->andWhere('omh.hebergement_id = :hebergement OR omh.option_maximum IS NULL')
        ->setParameter('hebergement', $reservation->hebergement->getId())
        ->andWhere('oms.saison_id = :saison OR oms.option_maximum_id IS NULL')
        ->setParameter('saison', $reservation->saison->getId())
        ->getQuery()
        );

        return $this->createQueryBuilder('om')
        ->leftJoin('option_maximum_hebergement','omh','om.id = omh.option_maximum_id')
        ->leftJoin('option_maximum_saison', 'oms', 'om.id = oms.option_maximumm_id')
        ->andWhere('omh.hebergement_id = :hebergement OR omh.option_maximum IS NULL')
        ->setParameter('hebergement', $reservation->hebergement->getId())
        ->andWhere('oms.saison_id = :saison OR oms.option_maximum_id IS NULL')
        ->setParameter('saison', $reservation->saison->getId())
        ->getQuery()
        ->getResult();




        // SELECT om.id FROM `option_maximum` AS om
        // LEFT JOIN `option_maximum_hebergement` AS omh ON om.id = omh.option_maximum_id
        // LEFT JOIN `option_maximum_saison` AS oms ON om.id = oms.option_maximum_id
        // WHERE (omh.hebergement_id = 1 OR omh.option_maximum_id IS NULL)
        // AND (oms.saison_id = 4 OR oms.option_maximum_id IS NULL);
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
