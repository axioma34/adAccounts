<?php

namespace App\Repository;

use App\Entity\AdAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdAccount[]    findAll()
 * @method AdAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdAccount::class);
    }

    // /**
    //  * @return AdAccount[] Returns an array of AdAccount objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdAccount
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
