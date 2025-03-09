<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panier>
 *
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
// Classe pour gérer les requêtes liées aux paniers.
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }


    // Methode pour rechercher le dernier panier de l'utilisateur.
    public function getLastCartOrder($userId)
{
    return $this->createQueryBuilder('p')
        ->leftJoin('p.order', 'c', 'WITH', 'c.Cart = p.id')
        ->where('p.Users = :userId')
        ->andWhere('c.Cart IS NULL')
        ->orderBy('p.id', 'DESC')
        ->setMaxResults(1)
        ->setParameter('userId', $userId)
        ->getQuery()
        ->getOneOrNullResult();
}


    //    /**
    //     * @return Panier[] Returns an array of Panier objects
    //     */
    //Methode pour rechercher un panier par l'id de l'utilisateur.
        public function findByUserId($value): array
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.Users = :val')
                ->setParameter('val', $value)
                ->orderBy('p.id', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getResult()
            ;
        }

    //    public function findOneBySomeField($value): ?Panier
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
