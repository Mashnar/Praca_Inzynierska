<?php

namespace App\Repository;

use App\Entity\DataMain;
use App\Entity\Device;
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

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @param Device $device
     * @param string $type
     * @return array
     */
    public function getDataBeetweenDate(DateTime $start, DateTime $end, Device $device, string $type): array
    {

        return $this->createQueryBuilder('d')
            ->select('d.' . $type . ',d.createdAt'
            )
            ->andWhere('d.createdAt BETWEEN  :start AND :end')
            ->andWhere('d.pm25 is NULL')
            ->andWhere('d.device = :device')
            ->setParameters(['start' => $start, 'end' => $end, 'device' => $device])
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Funkcja zwracajaca 24 ostatnich parametrow pogodowtych (wilgotnosc,cisnienie,temp) jeden z nich
     * @param Device $device
     * @param string $type
     * @return array
     */
    public function get48LatestParams(Device $device, string $type): array
    {

        return $this->createQueryBuilder('d')
            ->select('d.' . $type . ',d.createdAt'
            )
            ->where('d.pm25 is NULL')
            ->andWhere('d.device = :device')
            ->setParameters(['device' => $device])
            ->orderBy('d.createdAt', 'DESC')
            ->setMaxResults(48)
            ->getQuery()
            ->getArrayResult();
    }


    /**
     * Funkcja zwracajaca poluution po nazwie
     * @param DateTime $start
     * @param DateTime $end
     * @param Device $device
     * @return array
     */
    public function getPollutionByDate(DateTime $start, DateTime $end, Device $device): array
    {
        return $this->createQueryBuilder('d')
            ->select('d.pm10', 'd.pm25', 'd.createdAt'
            )
            ->where('d.device = :device')
            ->andWhere('d.createdAt BETWEEN  :start AND :end')
            //temperatura musi byc nullem aby nie bral niepotrzebnego
            ->andWhere('d.temperature is NULL')
            ->setParameters(['device' => $device, 'start' => $start, 'end' => $end])
            ->getQuery()
            ->getArrayResult();
    }


    /**
     * Funkcja zwracajca 4 ostatnie wartosci dla zanieczyszczeń
     * @param Device $device
     * @return array
     */
    public function get4LatestPollution(Device $device): array
    {
        return $this->createQueryBuilder('d')
            ->select('d.pm25', 'd.pm10', 'd.createdAt'
            )
            ->where('d.temperature is NULL')
            ->andWhere('d.device = :device')
            ->setParameters(['device' => $device])
            ->orderBy('d.createdAt', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getArrayResult();
    }

}
