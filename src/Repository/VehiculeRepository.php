<?php

namespace App\Repository;

use App\Entity\Vehicule;
use App\Entity\Reservation;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicule>
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }
    public function searchVehicules(array $criteria): array
    {
        $qb = $this->createQueryBuilder('v');

        if (!empty($criteria['marque'])) {
            $qb->andWhere('v.marque LIKE :marque')
               ->setParameter('marque', '%' . $criteria['marque'] . '%');
        }

        if (!empty($criteria['prixMax'])) {
            $qb->andWhere('v.prix <= :prixMax')
               ->setParameter('prixMax', $criteria['prixMax']);
        }

        if (isset($criteria['disponible']) && $criteria['disponible'] !== null) {
            $qb->andWhere('v.statut = :statut')
               ->setParameter('statut', $criteria['disponible']);
        }

        return $qb->getQuery()->getResult();
    }



    public function countReservationsByVehicule(Vehicule $vehicule): int
    {
        return $this->_em->createQueryBuilder()
            ->select('COUNT(r.id)')
            ->from(Reservation::class, 'r')
            ->where('r.vehicules = :vehicule')
            ->setParameter('vehicule', $vehicule)
            ->getQuery()
            ->getSingleScalarResult();
    }

    //    /**
    //     * @return Vehicule[] Returns an array of Vehicule objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vehicule
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
