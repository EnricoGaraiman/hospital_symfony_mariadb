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
        if($filters['medic'] !== ''){
            $qb->andWhere('c.medic = :medic')
            ->setParameter('medic', $filters['medic']);
        }
        if($filters['pacient'] !== ''){
            $qb->andWhere('c.pacient = :pacient')
                ->setParameter('pacient', $filters['pacient']);
        }
        if($filters['medicament'] !== ''){
            $qb->andWhere('c.medicament = :medicament')
                ->setParameter('medicament', $filters['medicament']);
        }
        if($filters['data1'] !== ''){
            $qb->andWhere('c.data >= :data1')
                ->setParameter('data1', $filters['data1']);
        }
        if($filters['data2'] !== ''){
            $qb->andWhere('c.data <= :data2')
                ->setParameter('data2', $filters['data2']);
        }

        if($getNumber === true) {
            $qb->select('count(distinct(c.id))');
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('c.data', 'DESC')
            ->setFirstResult(((int)$page - 1) * (int)$items)
            ->setMaxResults((int)$items);
        return $qb->getQuery()->getResult();
    }

    public function getConsultatiiForPacient($idPacient, $items, $page, $getNumber)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.pacient =:idPacient ')
            ->setParameter('idPacient', $idPacient);

        if($getNumber === true) {
            $qb->select('count(distinct(c.id))');
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('c.data', 'DESC')
            ->setFirstResult(((int)$page - 1) * (int)$items)
            ->setMaxResults((int)$items);
        return $qb->getQuery()->getResult();
    }

    public function getLastConsultatii($limit) {
        $qb = $this->createQueryBuilder('c');

        $qb->orderBy('c.data', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }

    public function getNumberOfMediciForPacient($idPacient) {
        $qb = $this->createQueryBuilder('c')
            ->where('c.pacient = :id')
            ->setParameter('id', $idPacient);
        $qb->select('count(distinct(c.medic))');
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getNumberOfConsultatiiForPacient($idPacient) {
        $qb = $this->createQueryBuilder('c')
            ->where('c.pacient = :id')
            ->setParameter('id', $idPacient);
        $qb->select('count(distinct(c.id))');
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getNumberOfMedicamenteForPacient($idPacient) {
        $qb = $this->createQueryBuilder('c')
            ->where('c.pacient = :id')
            ->setParameter('id', $idPacient);
        $qb->select('count(distinct(c.medicament))');
        return $qb->getQuery()->getSingleScalarResult();
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
