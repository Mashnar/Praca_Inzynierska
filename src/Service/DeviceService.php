<?php


namespace App\Service;

use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * Klasa służąca do zarządzania urządzeniami dostępnymi w serwisie (mikrokontrolery)
 * Class DeviceService
 * @package App\Service
 */
class DeviceService
{

    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var ValidatorInterface */
    private $validator;

    /**
     * DeviceService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
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
            $device = new Device();
            $device->setName($data['name']);
            $device->setIpAddress($data['ip_address']);
            $device->setActive(true);
        } catch (Error | Exception $e) {
            return new Response("Skontaktuj sie z administratorem, problem z nazewnictwem",
                RESPONSE::HTTP_NOT_ACCEPTABLE);


        }
        //Walidujemy nasz obiekt
        $errors = $this->validator->validate($device);
        //Jesli sa errory, zwracamy echo i konczymy dzialanie skryptu
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            echo $errorsString;
            die;
        }
        //Jesli jakiekolwiek problemy z wgrywaniem dnaych, tez umieszczamy w try catch i zwracamy aby sie skontaktowac
        //z administratorem, nie chce aby żadne komunikaty byly zwracane
        try {
            $this->entityManager->persist($device);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Error | Exception  $e) {
            $this->entityManager->rollback();

            return new Response("Problem z bazą danych, skontaktuj się z administratorem",
                RESPONSE::HTTP_NOT_ACCEPTABLE);

        }
        //Jesli wszystko odbędzie sie pozytywnie, zwracamy true
        return new Response("Dodane", Response::HTTP_OK);
    }
}