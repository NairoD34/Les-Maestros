<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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

    /**
     * @throws Exception
     */
    public function searchChildCategory($category): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT id FROM category WHERE parent_category_id = :categoryId";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery([
            'categoryId' => $category->getId()
        ]);

        $ids = $result->fetchFirstColumn();

        if (empty($ids)) {
            return [];
        }
        return $this->findBy(['id' => $ids]);
    }


    public function searchByName(string $title): ?array
    {
        return $this->createQueryBuilder('s')
            ->where('s.title like :val')
            ->setParameter('val', '%' . $title . '%')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
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

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
