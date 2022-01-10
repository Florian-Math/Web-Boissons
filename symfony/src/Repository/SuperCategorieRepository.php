<?php

namespace App\Repository;

use App\Entity\SuperCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SuperCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method SuperCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method SuperCategorie[]    findAll()
 * @method SuperCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SuperCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuperCategorie::class);
    }

    // /**
    //  * @return SuperCategorie[] Returns an array of SuperCategorie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SuperCategorie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
