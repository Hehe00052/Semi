<?php

namespace App\Repository;

use App\Entity\Idstaff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Idstaff>
 *
 * @method Idstaff|null find($id, $lockMode = null, $lockVersion = null)
 * @method Idstaff|null findOneBy(array $criteria, array $orderBy = null)
 * @method Idstaff[]    findAll()
 * @method Idstaff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdstaffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Idstaff::class);
    }

//    /**
//     * @return Idstaff[] Returns an array of Idstaff objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Idstaff
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
