<?php

namespace App\Repository;

use App\Entity\AlimentAlimentDepth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AlimentAlimentDepth|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlimentAlimentDepth|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlimentAlimentDepth[]    findAll()
 * @method AlimentAlimentDepth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlimentAlimentDepthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlimentAlimentDepth::class);
    }

    // /**
    //  * @return AlimentAlimentDepth[] Returns an array of AlimentAlimentDepth objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AlimentAlimentDepth
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
