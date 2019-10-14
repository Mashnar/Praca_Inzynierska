<?php


namespace App\Service;

use App\Entity\DataMain;
use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class TemperatureService
 * @package App\Service
 * Klasa służąca do zarządzania temperaturą w systemie.
 */
class TemperatureService
{

    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var ValidatorInterface */
    private $validator;

    /**
     * TemperatureService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param $data [array] Tablica z danymi na temat temperatury
     * @return Response
     */
    public function saveTemperature($data): Response
    {
        $this->entityManager->beginTransaction();
        $device = $this->entityManager->getRepository(Device::class)->find($data['device_id']);
        if (!$device) {
            return new Response("Nie znaleziono twojego urządzenia",
                RESPONSE::HTTP_NOT_ACCEPTABLE);
        }
        try {
            $temperature = new DataMain();
            $temperature->setTemperature($data['temperature']);
            $temperature->setPressure($data['pressure']);
            $temperature->setHumidity($data['humidity']);
            $temperature->setDevice($device);
        } catch (Error | Exception $e) {
            return new Response("Skontaktuj się z administratore,problem z nazwenictwem",
                RESPONSE::HTTP_NOT_ACCEPTABLE);

        }
        //Walidujemy nasz obiekt
        $errors = $this->validator->validate($temperature);
        //Jesli sa errory, zwracamy echo i konczymy dzialanie skryptu
        if (count($errors) > 0) {
            return new Response((string)$errors,
                RESPONSE::HTTP_NOT_ACCEPTABLE);
        }

        try {
            $this->entityManager->persist($temperature);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Error | Exception $e) {
            $this->entityManager->rollback();
            return new Response("Problem z bazą danych, skontaktuj się z administratorem",
                RESPONSE::HTTP_NOT_ACCEPTABLE);
        }

        return new Response("Dodane", Response::HTTP_OK);
    }
}