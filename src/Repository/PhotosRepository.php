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
// Classe pour gérer les requêtes liées aux photos.
class PhotosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photos::class);
    }

    // Methode pour rechercher une photo en fonction de la catégorie demandé.
    public function searchPhotoByCategory($category)
    {
        $sql = "select * from photos p  where p.category_id = ?";
        $query = $this->getEntityManager()->getConnection()
            ->executeQuery($sql, [$category->getId()]);
        $result = $query->fetchAllAssociative();
        $photos = [];
        foreach ($result as $var) {
            $photo = $this->find($var['id']);
            $photos[] = $photo;
        }
        return $photos;
    }

    // Methode pour rechercher une photo en fonction du produit demandé.
    public function searchOnePhotoByProduct($idProduct)
    {

        return $this->createQueryBuilder('p')
            ->where('p.product = :id')
            ->setParameter('id', $idProduct)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // Methode pour rechercher les photos en fonction du produit demandé.
    public function searchPhotoByProduct($idProduct)
    {

        return $this->createQueryBuilder('p')
            ->where('p.product = :id')
            ->setParameter('id', $idProduct)
            ->getQuery()
            ->getResult();
    }

    // Methode pour inserer une photo en fonction de la catégorie demandé.
    public function insertPhotoWithCategorie($id, $path)
    {
        $sql = "INSERT INTO `photos`(`category_id`, `url_photo`) VALUES ('" . $id . "','" . $path . "')";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }

    // Methode pour inserer une photo en fonction du produit demandé.
    public function insertPhotoWithProduct($id, $path)
    {
        $sql = "INSERT INTO `photos`(`product_id`, `url_photo`) VALUES ('" . $id . "','" . $path . "')";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }

    // Methode pour modifier une photo en fonction de la catégorie demandé.
    public function updatePhotoInCategory($id, $path)
    {
        $sql = "UPDATE `photos` SET url_photo = '$path' WHERE category_id =  $id ";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }

    // Methode pour modifier une photo en fonction du produit demandé.
    public function updatePhotoInProduct($id, $path)
    {
        $sql = "UPDATE `photos` SET url_photo = '$path' WHERE product_id =  $id ";
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
