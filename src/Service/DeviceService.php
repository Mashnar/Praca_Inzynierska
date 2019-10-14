<?php


namespace App\Service;

use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Exception;
use Symfony\Component\HttpFoundation\Response;


/**
 * Klasa służąca do zarządzania urządzeniami dostępnymi w serwisie (mikrokontrolery)
 * Class DeviceService
 * @package App\Service
 */
class DeviceService
{

    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var ValidationService */
    private $validationService;

    /**
     * DeviceService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ValidationService $validationService
     */
    public function __construct(EntityManagerInterface $entityManager, ValidationService $validationService)
    {
        $this->entityManager = $entityManager;
        $this->validationService = $validationService;
    }

    /**
     * @param $data
     * @return Response
     */
    public function saveFirstAccessDevice($data): Response
    {

        //Rozpoczynam transakcję
        $this->entityManager->beginTransaction();
        //Pierwszy try catch, wychwyca zle nazwy pól przesyłanych POST
        try {
            $device = $this->createDeviceObject($data);
        } catch (Error | Exception $e) {
            return new Response('Skontaktuj sie z administratorem, problem z nazewnictwem',
                RESPONSE::HTTP_NOT_ACCEPTABLE);
        }
        //waliduje obiekt
        $this->validationService->validate($device);

        //Jesli jakiekolwiek problemy z wgrywaniem dnaych, tez umieszczamy w try catch i zwracamy aby sie skontaktowac
        //z administratorem, nie chce aby żadne komunikaty byly zwracane
        try {
            $this->entityManager->persist($device);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Error | Exception  $e) {
            $this->entityManager->rollback();

            return new Response('Problem z bazą danych, skontaktuj się z administratorem',
                RESPONSE::HTTP_NOT_ACCEPTABLE);

        }
        //Jesli wszystko odbędzie sie pozytywnie, zwracamy true
        return new Response('Dodane', Response::HTTP_OK);
    }


    /**
     * Funkcja tworząca obiekt klasy Device
     * @param $data
     * @return Device
     */
    protected function createDeviceObject($data): Device
    {
        $device = new Device();
        $device->setName($data['name']);
        $device->setIpAddress($data['ip_address']);
        $device->setActive(true);
        return $device;
    }


}