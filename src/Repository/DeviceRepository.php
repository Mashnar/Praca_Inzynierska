<?php

namespace App\Repository;

use App\Entity\Device;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Device|null find($id, $lockMode = null, $lockVersion = null)
 * @method Device|null findOneBy(array $criteria, array $orderBy = null)
 * @method Device[]    findAll()
 * @method Device[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }

    // /**
    //  * @return Device[] Returns an array of Device objects
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
    public function findOneBySomeField($value): ?Device
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
     * Funkcja zwracajaca po nazwie obiekt klasy Device
     * @param string $value
     * @param bool $get_id (domyslnie false, jesli true to zwraca samo id
     * @return Device|null
     */
    public function findDeviceByName(string $value): ?Device
    {


        return $this->findOneBy(['name' => $value]);


    }


    /**
     * Funkcja zwracajaca po nazwie obiekt id obiektu Device
     * @param string $value
     * @return Device|null
     */
    public function getDeviceId(string $value): ?Int
    {

        return $this->findOneBy(['name' => $value])->getId();


    }


    /**
     * Funkcja zwracajaca po nazwie obiekt id obiektu Device
     * @param string $value
     * @return Device|null
     */
    public function getDeviceNameAndId(): array
    {
        //jesli paratemt get_id jest true, to zwracamy id tego
        return $this->createQueryBuilder('d')
            ->select('d.id,d.description,d.name')
            ->orderBy('d.name')
            ->getQuery()
            ->getArrayResult();


    }




}
