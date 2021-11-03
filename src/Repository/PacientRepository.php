<?php

namespace App\Repository;

use App\Entity\Pacient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pacient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pacient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pacient[]    findAll()
 * @method Pacient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PacientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pacient::class);
    }

    // /**
    //  * @return Pacient[] Returns an array of Pacient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pacient
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
