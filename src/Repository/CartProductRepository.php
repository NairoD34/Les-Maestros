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
class CartProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProduct::class);
    }

    public function AddProductToCartProduct($idProduct, $idCart, $qty)
    {
        $sql = "INSERT INTO `cart_product`(`product_id`, `cart_id`, `quantity`) VALUES (" . $idProduct . "," . $idCart . "," . $qty . ")";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }
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
    public function updateQuantityInCartProduct($qty, $idProduct, $idCart)
    {
        $sql = "UPDATE `panier_produit` SET `quantite`='$qty' WHERE produit_id = $idProduct and panier_id = $idCart ";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }

    public function removeProductFromCart($idProduct, $idCart)
    {
        return $this->createQueryBuilder('pp')
            ->delete()
            ->where('pp.product = :idProduct')
            ->andWhere('pp.Cart = :idCart')
            ->setParameter('idProduct', $idProduct)
            ->setParameter('idCart', $idCart)
            ->getQuery();

        return $qb->execute();
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
                ->getResult()
            ;
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
