<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchtags($term,$cat)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->orderBy('p.bgcolor', 'ASC');

            if (!empty($term)) {
                $queryBuilder->andWhere('p.tags LIKE :searchTerm')
                    ->setParameter('searchTerm', '%' . $term . '%');
            }

            if (!empty($cat)) {
                $queryBuilder->andWhere('p.service = :service')
                    ->setParameter('service', $cat);
            }

            $results = $queryBuilder->getQuery()
                ->execute();

            return $results;
    }

    // Custom query to find a post by name
   public function findOneByName($name)
   {
       return $this->createQueryBuilder('p')
           ->where('p.name = :name')
           ->setParameter('name', $name)
           ->getQuery()
           ->getOneOrNullResult();
   }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
