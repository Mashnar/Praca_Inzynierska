<?php


namespace App\Service;


use App\Entity\DataMain;
use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class PollutionService
{
    /** @var EntityManagerInterface */
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


    /** Funkcja do zapisywania pyłu zawieszonego
     * @param $data
     * @return Response
     */
    public function savePollution(array $data): Response
    {
        //rozpoczynamy transakcje
        $this->entityService->beginTransaction();

        if (!($this->validationService->checkVariableName($data))) {
            return new Response('Skontaktuj się z administratorem, problem z urządzeniem',
                RESPONSE::HTTP_NOT_ACCEPTABLE);
        }
        //Tutaj juz wiemy, ze dobrze mamy pole, wiec  bierzemy urzadzenie

        $device = $this->entityService->getDeviceByName($data['device_name']);
        if (!$device) {
            return new Response('Nie znaleziono twojego urządzenia',
                RESPONSE::HTTP_NOT_ACCEPTABLE);
        }


        try {
            //Tworze obiekt temperatury
            $pollution = $this->createPollutionObject($data, $device);
        } catch (Error | Exception $e) {
            return new Response('Skontaktuj się z administratorem',
                RESPONSE::HTTP_NOT_ACCEPTABLE);

        }


        $this->validationService->validate($pollution);


        try {
            $this->entityService->persistAndCommit($pollution);
        } catch (Error | Exception  $e) {
            $this->entityService->rollback();
            return new Response('Problem z bazą danych, skontaktuj się z administratorem',
                RESPONSE::HTTP_NOT_ACCEPTABLE);

        }
        //Jesli wszystko odbędzie sie pozytywnie, zwracamy true
        return new Response('Dodane', Response::HTTP_OK);


    }

    /**Funkcja tworząca obiekt dla pyłu zawieszonego
     * @param $data
     * @param $device
     * @return DataMain
     */
    protected function createPollutionObject(array $data, Device $device): DataMain
    {
        $pollution = new DataMain();
        $pollution->setPm10($data['pm10']);
        $pollution->setPm25($data['pm25']);
        $pollution->setDevice($device);
        return $pollution;
    }
}