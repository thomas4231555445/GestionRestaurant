<?php

namespace App\Repository;

use App\Entity\VinsCave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VinsCave>
 *
 * @method VinsCave|null find($id, $lockMode = null, $lockVersion = null)
 * @method VinsCave|null findOneBy(array $criteria, array $orderBy = null)
 * @method VinsCave[]    findAll()
 * @method VinsCave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinsCaveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VinsCave::class);
    }

    public function findByCaveId(int $caveId): array
    {
        return $this->createQueryBuilder('vc')
            ->andWhere('vc.id_cave = :caveId')
            ->setParameter('caveId', $caveId)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return VinsCave[] Returns an array of VinsCave objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VinsCave
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
