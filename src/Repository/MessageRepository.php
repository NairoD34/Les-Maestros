<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
// Classe pour gérer les requêtes liées aux messages.
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    // Methode pour rechercher un message par son Id.
    public function searchByName(string $id): ?array
    {
        return $this->createQueryBuilder('s')
            ->where('s.id like :val')
            ->setParameter('val', '%' . $id . '%')
            ->getQuery()
            ->getResult();
    }

    // Methode pour compter le nombre de messages.
    public function countMessages()
    {
        $result = $this->createQueryBuilder('e')
        ->select('COUNT(e) ')
        ->getQuery()->getSingleScalarResult();
           
           return $result;
        ;
    }
 

    //    /**
    //     * @return Message[] Returns an array of Message objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
