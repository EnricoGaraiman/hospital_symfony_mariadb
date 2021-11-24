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

    public function getConsultatiiByFilters($filters, $items, $page, $getNumber)
    {
        $qb = $this->createQueryBuilder('c');
        // filters
//        $qb->andWhere($qb->expr()->orX(
//            $qb->expr()->like('m.prenumeMedic', ':search'),
//            $qb->expr()->like('m.numeMedic', ':search'),
//            $qb->expr()->like('m.email', ':search'),
//            $qb->expr()->like('m.specializare', ':search')
//        ))
//            ->setParameter('search', '%'. $filters['medic'] . '%');

        if($getNumber === true) {
            $qb->select('count(distinct(c.id))');
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('c.data', 'DESC')
            ->setFirstResult(((int)$page - 1) * (int)$items)
            ->setMaxResults((int)$items);
        return $qb->getQuery()->getResult();
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
