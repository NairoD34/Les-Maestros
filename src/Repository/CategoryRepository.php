<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Categorie>
 *
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function searchParentCategory(string $name): ?array
    {
        return $this->createQueryBuilder('c')
            ->where('c.title like :title')
            ->andWhere('c.parent_category is null',)
            ->setParameter('title', '%' . $name . '%')
            ->getQuery()
            ->getResult();
    }
    public function findParentCategoryIdByChildId($childId): ?int
    {
        $result = $this->createQueryBuilder('c')
        ->select('IDENTITY(c.parent_category) AS parentId')
        ->where('c.id = :childId')
        ->setParameter('childId', $childId)
        ->getQuery()
        ->getOneOrNullResult();
        
        return $result['parentId'] ?? null;
        
    }

    public function searchChildCategory($category)
    {
        $sql = "select * from category c where c.parent_category_id = ?";
        $query = $this->getEntityManager()->getConnection()
            ->executeQuery($sql, [$category->getId()]);
        $result =  $query->fetchAllAssociative();
        $children = [];
        foreach ($result as $category) {
            $children = $this->find($category['id']);
            $children[] = $children;
        }   
        return $children;
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
        return $this->createQueryBuilder('c')
            ->setMaxResults(1)
            ->orderBy('c.id', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Categorie[] Returns an array of Categorie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Categorie
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
