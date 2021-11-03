<?php

namespace App\Repository;

use App\Entity\Consultatie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Consultatie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consultatie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consultatie[]    findAll()
 * @method Consultatie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultatieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consultatie::class);
    }

    // /**
    //  * @return Consultatie[] Returns an array of Consultatie objects
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
    public function findOneBySomeField($value): ?Consultatie
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
