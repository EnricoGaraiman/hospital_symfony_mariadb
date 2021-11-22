<?php

namespace App\Repository;

use App\Entity\Pacient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Pacient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pacient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pacient[]    findAll()
 * @method Pacient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PacientRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pacient::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Pacient) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getPacientiByFilters($filters, $items, $page, $getNumber)
    {
        $qb = $this->createQueryBuilder('p');
        // filters
        $qb->andWhere($qb->expr()->orX(
            $qb->expr()->like('p.prenumePacient', ':search'),
            $qb->expr()->like('p.numePacient', ':search'),
            $qb->expr()->like('p.email', ':search'),
            $qb->expr()->like('p.cnp', ':search')
        ))
            ->setParameter('search', '%'. $filters['pacient'] . '%');

        if($filters['asigurare'] !== "" and $filters['asigurare'] == 1) {
            $qb->andwhere('p.asigurare = 1');
        }
        else if($filters['asigurare'] !== "" and $filters['asigurare'] == 0) {
            $qb->andwhere('p.asigurare = 0');
        }

        if($getNumber === true) {
            $qb->select('count(distinct(p.id))');
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('p.id', 'DESC')
            ->setFirstResult(((int)$page - 1) * (int)$items)
            ->setMaxResults((int)$items);
        return $qb->getQuery()->getResult();
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
