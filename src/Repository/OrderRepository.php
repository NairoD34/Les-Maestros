<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    public function searchByName(string $id): ?array
    {
        return $this->createQueryBuilder('s')
            ->where('s.id like :val')
            ->setParameter('val', '%' . $id . '%')

            ->getQuery()
            ->getResult();
    }
    public function insertCommande($date, $total, $delivery, $payment, $user, $cart)
    {
        $sql = "INSERT INTO `commande`(`order_date`, `ti_order_price`, `delivery_id`, `payment_id`, `state_id`, `users_id`, `cart_id`) 
                VALUES ('" . $date . "'," . $total . "," . $delivery . "," . $payment . ",'1'," . $user . "," . $cart . ")";
        $this->getEntityManager()->getConnection()
            ->executeQuery($sql);
    }
    //    /**
    //     * @return Commande[] Returns an array of Commande objects
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

    //    public function findOneBySomeField($value): ?Commande
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
