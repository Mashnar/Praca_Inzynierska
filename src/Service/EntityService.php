<?php


namespace App\Service;


use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;

class EntityService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * EntityService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }


    /**Funkcja rozpoczynająca transakcje
     *
     */
    public function beginTransaction(): void
    {
        $this->entityManager->beginTransaction();
    }

    /** Funkcja szykujaca insert i commitująca
     * @param $object
     */
    public function persistAndCommit($object): void
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
        $this->entityManager->commit();
    }


    /**
     *Funkcja rollback transakcji
     */
    public function rollback(): void
    {
        $this->entityManager->rollback();
    }


    /** Funkcja zwracajaca obiekt urzadzenia za pomoca nazwy
     * @param $name
     * @return Device|null
     */
    public function getDevice(string $name): ?Device
    {
        return $this->entityManager->getRepository(Device::class)->findDeviceByName($name);
    }


}