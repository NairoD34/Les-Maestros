<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
// Classe pour gérer les opérations liées aux utilisateurs.
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    // Methode pour convertir l'objet en une chaîne de caractères.
    public function __toString()
    {
        return $this->name;
    }

    //    /**
    //     * @return Users[] Returns an array of Users objects
    //     */

    // Methode pour compter les utilisateurs.
    public function countUsers(): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e)')
            ->andWhere("e.roles NOT LIKE :role")
            ->setParameter('role', '%ROLE_ADMIN%')
            ->getQuery()
            ->getSingleScalarResult();
    }


    /**
     * @throws \JsonException
     */
    // Methode pour rechercher les utilisateurs par nom.
    public function searchByName(?string $name = '', ?string $lastname = 'ASC', ?string $firstname = 'ASC'): array
    {
        $qb = $this->createQueryBuilder('s');

        if (!empty($name)) {
            $qb->andWhere('LOWER(s.lastname) LIKE LOWER(:name)')
                ->setParameter('name', '%' . $name . '%');
        }

        $qb->andWhere('s.roles LIKE :role')
            ->setParameter('role', '%"ROLE_ADMIN"%');

        $qb->addOrderBy('s.lastname', $lastname)
            ->addOrderBy('s.firstname', $firstname);

        return $qb->getQuery()->getResult();
    }


    // Methode pour rechercher les clients.
    public function searchByClients(?string $name = '', ?string $lastname = 'ASC', ?string $firstname = 'ASC'): array
    {
        $qb = $this->createQueryBuilder('s');

        if (!empty($name)) {
            $qb->andWhere('LOWER(s.lastname) LIKE LOWER(:name)')
                ->setParameter('name', '%' . $name . '%');
        }

        $qb->andWhere('s.roles NOT LIKE :role')
            ->setParameter('role', '%"ROLE_ADMIN"%');

        $qb->addOrderBy('s.lastname', $lastname)
            ->addOrderBy('s.firstname', $firstname);

        return $qb->getQuery()->getResult();
    }


}
