<?php

namespace App\Repository;

use App\Entity\TeamWeek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TeamWeek|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamWeek|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamWeek[]    findAll()
 * @method TeamWeek[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamWeekRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamWeek::class);
    }

    // /**
    //  * @return TeamWeek[] Returns an array of TeamWeek objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeamWeek
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
