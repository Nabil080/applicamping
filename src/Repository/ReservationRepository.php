<?php

namespace App\Repository;

use App\Entity\Emplacement;
use App\Entity\Reservation;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findByEmplacementAndDates(Emplacement $emplacement, DateTime $start, DateTime $end): ?Reservation
    {
        $qb = $this->createQueryBuilder('reservation')
            // condition de date :
            ->andWhere('reservation.debut BETWEEN :debut AND :fin')
            ->orWhere('reservation.fin BETWEEN :debut AND :fin')
            ->orWhere('reservation.debut < :debut AND reservation.fin > :fin')
            ->orWhere('reservation.debut > :debut AND reservation.fin < :fin')
            ->setParameter('debut', $start)
            ->setParameter('fin', $end);

        // condition d'emplacement
        $qb->andWhere('reservation.emplacement = :emplacement')
            ->setParameter('emplacement', $emplacement);

        // dd($qb->getQuery());

        return $qb->getQuery()->getOneOrNullResult();
    }

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
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

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
