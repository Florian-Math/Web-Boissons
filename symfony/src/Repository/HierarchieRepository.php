<?php

namespace App\Repository;

use App\Entity\Hierarchie;
use App\Entity\SousCategorie;
use App\Entity\SuperCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hierarchie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hierarchie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hierarchie[]    findAll()
 * @method Hierarchie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HierarchieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hierarchie::class);
    }

    public function findRoots()
    {
        $super = $this->getEntityManager()->getRepository(SuperCategorie::class)
            ->createQueryBuilder('super')
            ->select('super.aliment');

        $q = $this->createQueryBuilder('h');
        return
            $q->where($q->expr()->notIn('h.nomAliment', $super->getDQL()))
            ->getQuery()
            ->getResult()
            ;
    }

    public function findBySuperCategorie($superCategorie)
    {
        $sous = $this->getEntityManager()->getRepository(SousCategorie::class)
            ->createQueryBuilder('sous')
            ->select('sous.sousCategorie')
            ->where('sous.aliment = :aliment');


        $q = $this->createQueryBuilder('h');
        return
            $q->where($q->expr()->in('h.nomAliment', $sous->getDQL()))
                ->setParameter('aliment', $superCategorie)
                ->getQuery()
                ->getResult()
            ;
    }

    public function findSuperCategories($aliment)
    {
        $super = $this->getEntityManager()->getRepository(SuperCategorie::class)
            ->createQueryBuilder('super')
            ->select('super.superCategorie')
            ->where('super.aliment = :aliment');


        $q = $this->createQueryBuilder('h');
        return
            $q->where($q->expr()->in('h.nomAliment', $super->getDQL()))
                ->setParameter('aliment', $aliment)
                ->getQuery()
                ->getResult()
            ;
    }

    public function findSousCategories($aliment)
    {
        $sous = $this->getEntityManager()->getRepository(SousCategorie::class)
            ->createQueryBuilder('sous')
            ->select('sous.sousCategorie')
            ->where('sous.aliment = :aliment');


        $q = $this->createQueryBuilder('h');
        return
            $q->where($q->expr()->in('h.nomAliment', $sous->getDQL()))
                ->setParameter('aliment', $aliment)
                ->getQuery()
                ->getResult()
            ;
    }

    // /**
    //  * @return Hierarchie[] Returns an array of Hierarchie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hierarchie
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
