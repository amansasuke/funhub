<?php

namespace App\Repository;

use App\Entity\Documentsforproduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Documentsforproduct>
 *
 * @method Documentsforproduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method Documentsforproduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method Documentsforproduct[]    findAll()
 * @method Documentsforproduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentsforproductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Documentsforproduct::class);
    }

    public function add(Documentsforproduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Documentsforproduct $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Documentsforproduct[] Returns an array of Documentsforproduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Documentsforproduct
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
