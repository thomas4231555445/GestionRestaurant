<?php

namespace App\Repository;

use App\Entity\Vins;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vins>
 *
 * @method Vins|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vins|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vins[]    findAll()
 * @method Vins[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VinsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vins::class);
    }

    public function findByRestaurantId(int $restaurantId): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.id_restaurant = :restaurantId')
            ->setParameter('restaurantId', $restaurantId)
            ->getQuery()
            ->getResult();
    }

    public function findByCodeVin(string $code_vin): ?Vins
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.code_vin = :codeVin')
            ->setParameter('codeVin', $code_vin)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByCodeVinAndRestaurant(string $code_vin, int $id_restaurant): ?Vins
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.code_vin = :codeVin')
            ->andWhere('v.restaurant = :idRestaurant')
            ->setParameter('codeVin', $code_vin)
            ->setParameter('idRestaurant', $id_restaurant)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return Vins[] Returns an array of Vins objects
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

//    public function findOneBySomeField($value): ?Vins
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
