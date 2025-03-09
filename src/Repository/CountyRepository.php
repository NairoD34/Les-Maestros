<?php

namespace App\Repository;

use App\Entity\County;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Departement>
 *
 * @method Departement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Departement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Departement[]    findAll()
 * @method Departement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
// Classe pour gérer les requêtes liées aux counties ou départements.
class CountyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, County::class);
    }

    // Methode pour rechercher un county par son nom.
    public function findByRegionId($regionId)
    {
        return $this->createQueryBuilder('d')
            ->join('d.region_id', 'r')  // Supposons que votre relation avec la région s'appelle "region"
            ->where('r.id = :regionId')
            ->setParameter('regionId', $regionId)
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Departement[] Returns an array of Departement objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Departement
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
