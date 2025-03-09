<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ville>
 *
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
// Classe pour gérer les requêtes liées aux villes.
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    // Methode pour rechercher une ville par son nom avec la region associee.
    public function findOneByNameWithRegion($cityName): ?City
    {
        $cityName = strtoupper($cityName);

        return $this->createQueryBuilder('v')
            ->andWhere('v.name = :name')
            ->leftJoin('v.County', 'd')
            ->leftJoin('d.region', 'r')
            ->addSelect('d', 'r')
            ->setParameter('name', $cityName)
            ->getQuery()
            ->getOneOrNullResult();
    }
    // Methode pour rechercher une ville par son nom.
    public function searchByName(string $name): ?array
    {
        return $this->createQueryBuilder('v')
            ->where('v.name like :val')
            ->setParameter('val', '%' . $name . '%')
            ->addOrderBy('v.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Ville[] Returns an array of Ville objects
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

    //    public function findOneBySomeField($value): ?Ville
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
