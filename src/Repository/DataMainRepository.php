<?php

namespace App\Repository;

use App\Entity\DataMain;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DataMain|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataMain|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataMain[]    findAll()
 * @method DataMain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataMainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataMain::class);
    }

    // /**
    //  * @return DataMain[] Returns an array of DataMain objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataMain
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getTemperatureBetweenDate(DateTime $start, DateTime $end): array
    {
        return $this->createQueryBuilder('d')
            ->select('d.createdAt,d.temperature,d.humidity,d.pressure')
            ->andWhere('d.createdAt BETWEEN  :start AND :end')
            ->andWhere('d.pm25 is NULL')
            ->setParameters(['start' => $start, 'end' => $end])
            ->getQuery()
            ->getArrayResult();
    }
}
