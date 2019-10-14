<?php


namespace App\Service;

use App\Entity\DataMain;
use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TemperatureService
 * @package App\Service
 * Klasa służąca do zarządzania temperaturą w systemie.
 */
class TemperatureService
{

    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var ValidationService */
    private $validationService;


    /**
     * TemperatureService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ValidationService $validationService
     */
    public function __construct(EntityManagerInterface $entityManager, ValidationService $validationService)
    {
        $this->entityManager = $entityManager;
        $this->validationService = $validationService;
    }

    /**
     * @param $data [array] Tablica z danymi na temat temperatury
     * @return Response
     */
    public function saveTemperature($data): Response
    {
        $this->entityManager->beginTransaction();
        //Jesli nie istnieje taki klucz w tablicy, to zwracamy Response ze nie ma odpowiedniego pola
        if (!(array_key_exists('device_name', $data))) {
            return new Response('Skontaktuj się z administratorem, problem z nazewnictwem',
                RESPONSE::HTTP_NOT_ACCEPTABLE);
        }
        //Tutaj juz wiemy, ze dobrze mamy pole
        $device = $this->entityManager->getRepository(Device::class)->findDeviceByName($data['device_name']);



        if (!$device) {
            return new Response('Nie znaleziono twojego urządzenia',
                RESPONSE::HTTP_NOT_ACCEPTABLE);
        }


        try {
            $temperature = $this->createObjectTemperature($data, $device);
        } catch (Error | Exception $e) {
            return new Response('Skontaktuj się z administratorem, problem z nazewnictwem',
                RESPONSE::HTTP_NOT_ACCEPTABLE);

        }
        //waliduje obiekt
        $this->validationService->validate($temperature);


        try {
            $this->entityManager->persist($temperature);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Error | Exception $e) {
            $this->entityManager->rollback();
            return new Response('Problem z bazą danych, skontaktuj się z administratorem',
                RESPONSE::HTTP_NOT_ACCEPTABLE);
        }

        return new Response('Dodane', Response::HTTP_OK);
    }


    /**
     * Funkcja tworząca obiekt z temperaturą
     * @param $data
     * @param $device
     * @return DataMain
     */
    protected function createObjectTemperature($data, $device): DataMain
    {
        $temperature = new DataMain();
        $temperature->setTemperature($data['temperature']);
        $temperature->setPressure($data['pressure']);
        $temperature->setHumidity($data['humidity']);
        $temperature->setDevice($device);
        return $temperature;
    }
}