<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @extends ServiceEntityRepository<Orders>
 *
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
// Classe pour gérer les requêtes liées aux commandes.
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    // Methode pour rechercher une commande par son Id.
    public function searchByName(string $id): ?array
    {
        return $this->createQueryBuilder('s')
            ->where('s.id like :val')
            ->setParameter('val', '%' . $id . '%')
            ->getQuery()
            ->getResult();
    }

    // Methode pour compter le nombre de commandes.
    public function countOrders()
    {
        $result = $this->createQueryBuilder('e')
            ->select('COUNT(e) ')
            ->getQuery()->getSingleScalarResult();

        return $result;

    }

    // Methode pour compter le nombre de commandes avec le prix de la commande TTC.
    public function countTaxIncludedRevenue()
    {
        $result = $this->createQueryBuilder('e')
            ->select('SUM(e.ti_order_price) ')
            ->getQuery()->getSingleScalarResult();

        return $result;
    }



    //    /**
    //     * @return Orders[] Returns an array of Orders objects
    //     */
       public function findByUserID($value): array
       {
           return $this->createQueryBuilder('c')
               ->andWhere('c.users = :val')
               ->setParameter('val', $value)
               ->orderBy('c.id', 'ASC')
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Orders
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
