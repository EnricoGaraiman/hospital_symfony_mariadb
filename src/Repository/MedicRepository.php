<?php

namespace App\Repository;

use App\Entity\Medic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Medic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medic[]    findAll()
 * @method Medic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medic::class);
    }

    // /**
    //  * @return Medic[] Returns an array of Medic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Medic
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
