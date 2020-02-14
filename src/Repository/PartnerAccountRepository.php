<?php

namespace App\Repository;

use App\Entity\PartnerAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PartnerAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method PartnerAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method PartnerAccount[]    findAll()
 * @method PartnerAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartnerAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PartnerAccount::class);
    }

    // /**
    //  * @return PartnerAccount[] Returns an array of PartnerAccount objects
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
    public function findOneBySomeField($value): ?PartnerAccount
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
