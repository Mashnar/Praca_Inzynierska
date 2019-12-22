<?php


namespace App\Service;


use App\Entity\CategoriesWebsite;
use App\Entity\Consultation;
use App\Entity\DataMain;
use App\Entity\Device;
use App\Entity\WebsitePosts;
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

    public function getAllTeachersShift(): array
    {
        return $this->entityManager->getRepository(Consultation::class)->findAll();
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
     * Funkcja zwracajca najnowsze 3 wpisy dla danego kontrolera
     * @param string $name nazwa mikrokontrolera (SDS_TEMP1-4)
     * @return array
     */
    public function getNewestWeatherParameter(string $name): array
    {
        return $this->entityManager->getRepository(DataMain::class)->getNewestParameter($this->getDeviceByName($name));
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
     * FUnkcja zwracajaca ostatnie 24 lub 48  parametrów ( tylko gdy data jnie pasuje bedzie wykonywane)
     * @param int $id
     * @param string $type
     * @param Device|null $device
     * @param int $numberParameters
     * @return array
     */
    public
    function get48Or24LatestParams(int $id = null, string $type, Device $device = null, int $numberParameters = 48): array
    {

        if ($device) {
            return array_reverse($this->entityManager->getRepository(DataMain::class)->get48Or24LatestParams($device, $type, $numberParameters));
        }
        //odwracam,aby byly najnowsze od najstarszego do najmlodszego
        return array_reverse($this->entityManager->getRepository(DataMain::class)->get48Or24LatestParams($this->getDeviceById($id), $type, $numberParameters));
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
     * Funkcja zwracajaca X ostatnie wpisy zanieczyszczen
     * @param int $id domyslnie null
     * @param bool $with_id jesli true , to szukamy z id, jesli false to uzywamy urzadzenia domyslnego
     * @param Device|null $device
     * @param int $number_of_results
     * @return array
     */
    public
    function getXLatestPollution(int $id = null, $with_id = true, Device $device = null,int $number_of_results): array
    {
        //jesli true, to bierzemy po id
        if ($with_id) {
            //odwracam aby miec od najstarszej do najnowszej
            return array_reverse($this->entityManager->getRepository(DataMain::class)->getXLatestPollution($this->getDeviceById($id),$number_of_results));
        }

        //jesli nie, to bierzemy po urzadzeniu bo mamy ogolny wykres i przekazuje w parametrze urzadzenie
        return array_reverse($this->entityManager->getRepository(DataMain::class)->getXLatestPollution($device,$number_of_results));

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
     * Funkcja czyszczaca tabele dla danej encji
     * @param string $name
     */
    public function clearTable(string $name): void
    {
        $this->entityManager->getRepository($name)->clearTable();
    }

    /**
     * Funckja zwracajacca wszstkie kategorie z bazy danych
     * @return array
     */
    public function getAllCategories(): array
    {
        return $this->entityManager->getRepository(CategoriesWebsite::class)->findAll();
    }


    /**
     * Funkcja zwracajca wszystkie posty
     * @return CategoriesWebsite[]|WebsitePosts|WebsitePosts[]|object[]
     */
    public function getNews()
    {
        return $this->entityManager->getRepository(WebsitePosts::class)->findAll();
    }


}