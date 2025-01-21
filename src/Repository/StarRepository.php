<?php

namespace App\Repository;

use App\Entity\Star;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Star>
 *
 * @method Star|null find($id, $lockMode = null, $lockVersion = null)
 * @method Star|null findOneBy(array $criteria, array $orderBy = null)
 * @method Star[]    findAll()
 * @method Star[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Star::class);
    }

    public function findByUserAndBaseVins(int $userId, int $baseVinsId): ?Star
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id_users = :userId')
            ->andWhere('s.baseVins = :baseVinsId')
            ->setParameter('userId', $userId)
            ->setParameter('baseVinsId', $baseVinsId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByBaseVins(int $baseVinsId): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.baseVins = :baseVinsId')
            ->setParameter('baseVinsId', $baseVinsId)
            ->getQuery()
            ->getResult();
    }



//    /**
//     * @return Star[] Returns an array of Star objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Star
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
