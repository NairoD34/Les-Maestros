<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function searchNew()
    {
        return $this->createQueryBuilder('p')
            ->setMaxResults(6)
            ->OrderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findProduitsByCategorieId($categorieId)
    {
        return $this->createQueryBuilder('p')
            ->where('p.categorie = :categorieId')
            ->setParameter('categorieId', $categorieId)
            ->getQuery()
            ->getResult();
    }

    public function searchByName(string $libelle): ?array
    {
        return $this->createQueryBuilder('s')
            ->where('s.libelle like :val')
            ->setParameter('val', '%' . $libelle . '%')
            ->getQuery()
            ->getResult();
    }

    public function getLastId()
    {
        return $this->createQueryBuilder('p')
            ->setMaxResults(1)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTopPromoProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.promotion', 'pr')
            ->orderBy('pr.Taux_promotion', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Produit[] Returns an array of Produit objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
