<?php


namespace App\Service;


use App\Entity\CategoriesWebsite;
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
        $this->persist($object);
        $this->flush();
        $this->commit();

    }


    public function persist($object): void
    {
        $this->entityManager->persist($object);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function commit(): void
    {
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
    public
    function getDeviceByName(string $name): ?Device
    {
        return $this->entityManager->getRepository(Device::class)->findDeviceByName($name);
    }

    /** Funkcja zwracajaca obiekt urzadzenia za pomoca id
     * @param int $id
     * @return Device|null
     */
    public
    function getDeviceById(int $id): ?Device
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
    public
    function getWeatherParametersBeetweenDate(DateTime $start, DateTime $end, int $id, string $type): array
    {

        return $this->entityManager->getRepository(DataMain::class)->getDataBeetweenDate($start, $end, $this->getDeviceById($id), $type);
    }

    /**
     * Funkcja zwracajaca wszystkie urzadzenia wraz z ich nazwa i id
     * @return array
     */
    public
    function getDeviceAndId(): array
    {
        return $this->entityManager->getRepository(Device::class)->getDeviceNameAndId();
    }

    /**
     * FUnkcja zwracajaca ostatnie 24 parametry ( tylko gdy data jnie pasuje bedzie wykonywane)
     * @param int $id
     * @param string $type
     * @return array
     */
    public
    function get24LatestParams(int $id, string $type): array
    {

        //odwracam,aby byly najnowsze od najstarszego do najmlodszego
        return array_reverse($this->entityManager->getRepository(DataMain::class)->get24LatestParams($this->getDeviceById($id), $type));
    }


    /**
     * Funkcja zwracajaca id za pomoca nazwy urzadzenia
     * @param string $value
     * @return int
     */
    public
    function getIdByName(string $value): int
    {
        return $this->entityManager->getRepository(Device::class)->getDeviceId($value);
    }

    /**
     * Funkcja zwracajca wartosci zanieszczyczenia w powietrzu po dacie
     * @param DateTime $start
     * @param DateTime $end
     * @param int $id
     * @return array
     */
    public
    function getPollution(DateTime $start, DateTime $end, int $id): array
    {
        return $this->entityManager->getRepository(DataMain::class)->getPollutionByDate($start, $end, $this->getDeviceById($id));
    }

    /**
     * Funkcja zwracajaca 4 ostatnie wpisy zanieczyszczen
     * @param int $id
     * @return array
     */
    public
    function get4LatestPollution(int $id): array
    {
        //odwracam aby miec od najstarszej do najnowszej
        return array_reverse($this->entityManager->getRepository(DataMain::class)->get4LatestPollution($this->getDeviceById($id)));
    }


    /**
     * Funkcja sprawdzajaca czy istnieje kategoria o danym slugu i id
     * @param int $id
     * @param string $slug
     * @return bool
     */
    public function ifCategoryExistBySlugAndId(int $id, string $slug): bool
    {
        if ($this->entityManager->getRepository(CategoriesWebsite::class)->findCategoryBySlugAndId($slug, $id)) {
            return true;
        }
        return false;
    }


    /**
     * @param string $name
     */
    public function clearTable(string $name): void
    {
        $this->entityManager->getRepository($name)->clearTable();
    }

    public function getAllCategories(): array
    {
        return $this->entityManager->getRepository(CategoriesWebsite::class)->findAll();
    }


}