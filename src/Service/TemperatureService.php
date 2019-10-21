<?php


namespace App\Service;

use App\Entity\DataMain;
use App\Entity\Device;
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

    /** @var $entityService */
    private $entityService;
    /** @var ValidationService */
    private $validationService;


    /**
     * TemperatureService constructor.
     * @param EntityService $entityService
     * @param ValidationService $validationService
     */
    public function __construct(EntityService $entityService, ValidationService $validationService)
    {
        $this->entityService = $entityService;
        $this->validationService = $validationService;
    }

    /**
     * @param $data [array] Tablica z danymi na temat temperatury
     * @return Response
     */
    public function saveTemperature(array $data): Response
    {
        $this->entityService->beginTransaction();
        //Jesli nie ma odpowiedniej zmiennej, zwracamy response
        if (!($this->validationService->checkVariableName($data))) {

            return new Response('Skontaktuj się z administratorem',
                RESPONSE::HTTP_NOT_ACCEPTABLE);
        }
        //Tutaj juz wiemy, ze dobrze mamy pole
        $device = $this->entityService->getDevice($data['device_name']);


        if (!$device) {
            return new Response('Nie znaleziono twojego urządzenia',
                RESPONSE::HTTP_NOT_ACCEPTABLE);
        }


        try {
            //Tworze obiekt temperatury
            $temperature = $this->createObjectTemperature($data, $device);
        } catch (Error | Exception $e) {
            echo $e->getMessage();
            return new Response('Skontaktuj się z administratorem',
                RESPONSE::HTTP_NOT_ACCEPTABLE);

        }
        //waliduje obiekt
        $this->validationService->validate($temperature);


        try {
            $this->entityService->persistAndCommit($temperature);
        } catch (Error | Exception $e) {
            $this->entityService->rollback();
            return new Response('Problem z bazą danych',
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
    protected function createObjectTemperature(array $data, Device $device): DataMain
    {
        $temperature = new DataMain();
        $temperature->setTemperature($data['temperature']);
        $temperature->setPressure($data['pressure']);
        $temperature->setHumidity($data['humidity']);
        $temperature->setDevice($device);
        return $temperature;
    }


}