<?php

namespace App\Repository;

use App\Entity\ComposantRecette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ComposantRecette|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComposantRecette|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComposantRecette[]    findAll()
 * @method ComposantRecette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComposantRecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComposantRecette::class);
    }

    // /**
    //  * @return ComposantRecette[] Returns an array of ComposantRecette objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComposantRecette
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
