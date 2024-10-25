<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ville>
 *
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function findOneByNameWithRegion($cityName): ?City
    {
        $cityName = strtoupper($cityName); // Convertir le nom de la ville en majuscules

        return $this->createQueryBuilder('v')
            ->andWhere('v.name = :name')
            ->leftJoin('v.County', 'd')
            ->leftJoin('d.region', 'r')
            ->addSelect('d', 'r')
            ->setParameter('name', $cityName)
            ->getQuery()
            ->getOneOrNullResult();
    }
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
