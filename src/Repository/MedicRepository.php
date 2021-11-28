<?php

namespace App\Repository;

use App\Entity\Medic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Medic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medic[]    findAll()
 * @method Medic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medic::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Medic) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getMediciByFilters($filters, $items, $page, $getNumber)
    {
        $qb = $this->createQueryBuilder('m');
            // filters
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('m.prenumeMedic', ':search'),
                $qb->expr()->like('m.numeMedic', ':search'),
                $qb->expr()->like('m.email', ':search'),
                $qb->expr()->like('m.specializare', ':search')
            ))
            ->setParameter('search', '%'. $filters['medic'] . '%');

            if($filters['administrator'] !== "" and $filters['administrator'] == 1) {
                $qb->andwhere('m.roles LIKE :roles')
                    ->setParameter('roles', '%"' . 'ROLE_ADMIN' . '"%');
            }
            else if($filters['administrator'] !== "" and $filters['administrator'] == 0) {
                $qb->andwhere('m.roles NOT LIKE :roles')
                    ->setParameter('roles', '%"' . 'ROLE_ADMIN' . '"%');
            }

        if($getNumber === true) {
            $qb->select('count(distinct(m.id))');
            return $qb->getQuery()->getSingleScalarResult();
        }

        $qb->orderBy('m.id', 'DESC')
            ->setFirstResult(((int)$page - 1) * (int)$items)
            ->setMaxResults((int)$items);
        return $qb->getQuery()->getResult();
    }

    public function getMediciForConsultatie($search)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('CONCAT(m.prenumeMedic, \' \', m.numeMedic, \' \', m.email) as text', 'm.id')
            ->orWhere('m.prenumeMedic LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orWhere('m.numeMedic LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orWhere('m.email LIKE :search')
            ->setParameter('search', '%' . $search . '%');
        return $qb->getQuery()->getResult();
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
