<?php

namespace App\Repository;

use App\Entity\Photos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Photos>
 *
 * @method Photos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photos[]    findAll()
 * @method Photos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photos::class);
    }
    public function searchPhotoByCategory($category)
    {
        $sql = "select * from photos p  where p.category_id = ?";
        $query = $this->getEntityManager()->getConnection()
            ->executeQuery($sql, [$category->getId()]);
        $result =  $query->fetchAllAssociative();
        $photos = [];
        foreach ($result as $var) {
            $photo = $this->find($var['id']);
            $photos[] = $photo;
        }
        return $photos;
    }
    public function searchOnePhotoByProduct($idProduct)
    {

        return $this->createQueryBuilder('p')
            ->where('p.product = :id')
            ->setParameter('id',   $idProduct)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function searchPhotoByProduct($idProduct)
    {

        return $this->createQueryBuilder('p')
            ->where('p.product = :id')
            ->setParameter('id',   $idProduct)
            ->getQuery()
            ->getResult();
    }

    public function insertPhotoWithCategorie($id, $path)
    {
        $sql = "INSERT INTO `photos`(`category_id`, `url_photo`) VALUES ('" . $id . "','" . $path . "')";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }
    public function insertPhotoWithProduct($id, $path)
    {
        $sql = "INSERT INTO `photos`(`product_id`, `url_photo`) VALUES ('" . $id . "','" . $path . "')";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }

    public function updatePhotoInCategory($id, $path)
    {
        $sql = "UPDATE `photos` SET url_photo = '$path' WHERE category_id =  $id ";
        var_dump($sql);
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }

    public function updatePhotoInProduct($id, $path)
    {
        $sql = "UPDATE `photos` SET url_photo = '$path' WHERE product_id =  $id ";
        var_dump($sql);
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }
    //    /**
    //     * @return Photos[] Returns an array of Photos objects
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

    //    public function findOneBySomeField($value): ?Photos
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
