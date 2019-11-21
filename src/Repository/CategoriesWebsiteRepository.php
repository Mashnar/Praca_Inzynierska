<?php

namespace App\Repository;

use App\Entity\CategoriesWebsite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CategoriesWebsite|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriesWebsite|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriesWebsite[]    findAll()
 * @method CategoriesWebsite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesWebsiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriesWebsite::class);
    }

    // /**
    //  * @return CategoriesWebsite[] Returns an array of CategoriesWebsite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoriesWebsite
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findCategoryBySlugAndId(string $slug, int $id): ?array
    {
        return $this->createQueryBuilder('d')
            ->select('d.id')
            ->where('d.category_slug_id = :id')
            ->andWhere('d.slug =:slug')->setParameters(
                ['id' => $id
                    , 'slug' => $slug])
            ->getQuery()->getResult();
    }
}
