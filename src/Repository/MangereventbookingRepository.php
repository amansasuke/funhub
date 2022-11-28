<?php

namespace App\Repository;

use App\Entity\Mangereventbooking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mangereventbooking>
 *
 * @method Mangereventbooking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mangereventbooking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mangereventbooking[]    findAll()
 * @method Mangereventbooking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MangereventbookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mangereventbooking::class);
    }

    public function add(Mangereventbooking $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Mangereventbooking $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Mangereventbooking[] Returns an array of Mangereventbooking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mangereventbooking
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
