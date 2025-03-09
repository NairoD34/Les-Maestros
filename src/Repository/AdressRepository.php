<?php

namespace App\Repository;

use App\Entity\Adress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Adress>
 *
 * @method Adress|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adress|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adress[]    findAll()
 * @method Adress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
// Classe pour gérer les requêtes liées aux adresses dans le back-office.
class AdressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adress::class);
    }

    // Methode pour sauvegarder une adresse.
    public function save(Adress $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Methode pour rechercher une adresse et trier les adresses par asc ou desc.
    public function searchByName(string $name, string $trirue): ?array
    {
        return $this->createQueryBuilder('a')
            ->where('a.rue like :val')
            ->setParameter('val', '%' . $name . '%')
            ->addOrderBy('a.rue', $trirue)
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Adresse[] Returns an array of Adresse objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Adresse
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
