<?php

namespace App\Repository;

use App\Entity\TacheProjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TacheProjet|null find($id, $lockMode = null, $lockVersion = null)
 * @method TacheProjet|null findOneBy(array $criteria, array $orderBy = null)
 * @method TacheProjet[]    findAll()
 * @method TacheProjet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TacheProjetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TacheProjet::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('t')
            ->where('t.something = :value')->setParameter('value', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
