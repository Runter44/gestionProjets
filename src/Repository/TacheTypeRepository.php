<?php

namespace App\Repository;

use App\Entity\TypeTache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TacheType|null find($id, $lockMode = null, $lockVersion = null)
 * @method TacheType|null findOneBy(array $criteria, array $orderBy = null)
 * @method TacheType[]    findAll()
 * @method TacheType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TacheTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeTache::class);
    }

//    /**
//     * @return TacheType[] Returns an array of TacheType objects
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
    public function findOneBySomeField($value): ?TacheType
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
