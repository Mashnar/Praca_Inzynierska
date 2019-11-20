<?php

namespace App\Repository;

use App\Entity\WebsitePosts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method WebsitePosts|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebsitePosts|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebsitePosts[]    findAll()
 * @method WebsitePosts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsitePostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebsitePosts::class);
    }

    // /**
    //  * @return WebsitePosts[] Returns an array of WebsitePosts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WebsitePosts
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
