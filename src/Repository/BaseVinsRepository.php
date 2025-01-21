<?php

namespace App\Repository;

use App\Entity\BaseVins;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BaseVins>
 *
 * @method BaseVins|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseVins|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseVins[]    findAll()
 * @method BaseVins[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseVinsRepository extends ServiceEntityRepository
{

        public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseVins::class);
    }

        public function findAllOrderedByIdDesc()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return BaseVins[] Returns an array of BaseVins objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BaseVins
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
