<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function searchNew()
    {
        return $this->createQueryBuilder('p')
            ->setMaxResults(6)
            ->OrderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findProductsByCategoryId($categoryId)
    {
        return $this->createQueryBuilder('p')
            ->where('p.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getResult();
    }

    public function searchByName(string $title): ?array
    {
        return $this->createQueryBuilder('s')
            ->where('s.title like :val')
            ->setParameter('val', '%' . $title . '%')
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

    public function findTopSalesProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.sales', 'pr')
            ->orderBy('pr.sales_rate', 'ASC')
<<<<<<< Updated upstream
=======
            ->setMaxResults(3) // Pour limiter à 3 Products
>>>>>>> Stashed changes
            ->getQuery()
            ->getResult();
    }

<<<<<<< Updated upstream

    public function getSixRandomProducts(): array
    {
        $newProducts = $this->searchNew();

        $newProductIds = array_map(fn($product) => $product->getId(), $newProducts);

        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.id NOT IN (:newProductIds)')
            ->setParameter('newProductIds', $newProductIds)
            ->getQuery();

        $nonNewProducts = $queryBuilder->getResult();

        shuffle($nonNewProducts);

        return array_slice($nonNewProducts, 0, 6);
    }

//    /**
//     * @return Product[] Returns an array of Product objects
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

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
=======
    //    /**
    //     * @return Product[] Returns an array of Product objects
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
>>>>>>> Stashed changes
}
