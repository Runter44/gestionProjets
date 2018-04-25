<?php

namespace App\Repository;

use App\Entity\TypeProjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeProjet|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeProjet|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeProjet[]    findAll()
 * @method TypeProjet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeProjetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeProjet::class);
    }

//    /**
//     * @return TypeProjet[] Returns an array of TypeProjet objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeProjet
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
