<?php

namespace App\Repository;

use App\Entity\TypeTache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeTache|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeTache|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeTache[]    findAll()
 * @method TypeTache[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeTacheRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeTache::class);
    }

/*    /**
     * @return TypeTache[] Returns an array of TypeTache objects
     */
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
    public function findOneBySomeField($value): ?TypeTache
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
