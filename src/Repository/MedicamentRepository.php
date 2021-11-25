<?php

namespace App\Repository;

use App\Entity\Medicament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Medicament|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medicament|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medicament[]    findAll()
 * @method Medicament[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medicament::class);
    }

    public function getMedicamenteByFilters($filters, $items, $page, $getNumber)
    {
        $qb = $this->createQueryBuilder('m');
        // filters
        $qb->andWhere('m.denumire LIKE :search')
            ->setParameter('search', '%'. $filters['medicament'] . '%');

        if($getNumber === true) {
            $qb->select('count(distinct(m.id))');
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('m.id', 'DESC')
            ->setFirstResult(((int)$page - 1) * (int)$items)
            ->setMaxResults((int)$items);
        return $qb->getQuery()->getResult();
    }

    public function getMedicamenteForConsultatie($search)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m.denumire as text', 'm.id')
            ->where('m.denumire LIKE :search')
            ->setParameter('search', '%' . $search . '%');
        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Medicament[] Returns an array of Medicament objects
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
    public function findOneBySomeField($value): ?Medicament
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
