<?php

namespace App\Repository;

use App\Entity\CartProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartProduct>
 *
 * @method CartProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartProduct[]    findAll()
 * @method CartProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
// Classe pour gérer les requêtes liées aux produits dans le panier.
class CartProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProduct::class);
    }

    // Methode pour ajouter un produit dans le panier.
    public function AddProductToCartProduct($idProduct, $idCart, $qty)
    {
        $sql = "INSERT INTO `cart_product`(`product_id`, `cart_id`, `quantity`) VALUES (" . $idProduct . "," . $idCart . "," . $qty . ")";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
    }
    
    // Methode pour rechercher un produit dans le panier.
    public function getCartProductbyId($Product, $Cart)
    {
        return $this->createQueryBuilder('p')
            ->where('p.Product = :idproduct')
            ->andWhere('p.Cart = :idcart')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->setParameters(new ArrayCollection([
                new Parameter('idproduct', $Product),
                new Parameter('idcart', $Cart),
            ]))
            ->getQuery()
            ->getOneOrNullResult();
    }

//    public function updateQuantityInCartProduct($qty, $idProduct, $idCart)
//    {
//        $sql = "UPDATE `cart_product` SET `quantity`='$qty' WHERE product_id = $idProduct and cart_id = $idCart ";
//        $this->getEntityManager()->getConnection()
//            ->executeQuery($sql);
//    }

// Methode pour modifier la quantité d'un produit dans le panier.
    public function updateQuantityInCartProduct($qty, $idProduct, $idCart)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "UPDATE cart_product 
            SET quantity = :qty 
            WHERE product_id = :idProduct AND cart_id = :idCart";

        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'qty' => $qty,
            'idProduct' => $idProduct,
            'idCart' => $idCart,
        ]);
    }


    //    /**
    //     * @return CartProduct[] Returns an array of CartProduct objects
    //     */
    public function findByCartId($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.Cart = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?CartProduct
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
