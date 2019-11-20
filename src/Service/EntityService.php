<?php


namespace App\Service;


use App\Entity\DataMain;
use App\Entity\Device;
use DateTime;
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
    public function getDeviceByName(string $name): ?Device
    {
        return $this->entityManager->getRepository(Device::class)->findDeviceByName($name);
    }

    /** Funkcja zwracajaca obiekt urzadzenia za pomoca id
     * @param int $id
     * @return Device|null
     */
    public function getDeviceById(int $id): ?Device
    {
        return $this->entityManager->getRepository(Device::class)->find($id);
    }


    /**
     * Funkcja zwracająca tablice z temperaturami pomiedzy data startowa i koncowa
     * @param DateTime $start
     * @param DateTime $end
     * @param int $id
     * @param string $type
     * @return array
     */
    public function getTemperatureBetweenDate(DateTime $start, DateTime $end, int $id, string $type): array
    {

        return $this->entityManager->getRepository(DataMain::class)->getDataBeetweenDate($start, $end, $this->getDeviceById($id), $type);
    }

    /**
     * Funkcja zwracajaca wszystkie urzadzenia wraz z ich nazwa i id
     * @return array
     */
    public function getDeviceAndId(): array
    {
        return $this->entityManager->getRepository(Device::class)->getDeviceNameAndId();
    }

    /**
     * FUnkcja zwracajaca ostatnie 24 parametry ( tylko gdy data jnie pasuje bedzie wykonywane)
     * @param int $id
     * @param string $type
     * @return array
     */
    public function get24LatestParams(int $id, string $type): array
    {

        //odwracam,aby byly najnowsze od najstarszego do najmlodszego
        return array_reverse($this->entityManager->getRepository(DataMain::class)->get24LatestParams($this->getDeviceById($id), $type));
    }


    public function getIdByName(string $value): int
    {
        return $this->entityManager->getRepository(Device::class)->getDeviceId($value);
    }


}