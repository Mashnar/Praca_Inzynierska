<?php


namespace App\Service;

use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
     * @return bool
     * @throws Exception
     */
    public function saveFirstAccessDevice($data): bool
    {
        //Rozpoczynam transakcję
        $this->entityManager->beginTransaction();
        try {
            $device = new Device();
            $device->setName($data['name']);
            $device->setIpAddress($data['ip_address']);
            $device->setActive(true);
            //Walidujemy nasz obiekt
            $errors = $this->validator->validate($device);
            if (count($errors) > 0) {
                $errorsString = (string)$errors;
                echo $errorsString;
                die;
            }
            $this->entityManager->persist($device);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();
            echo 'Skontaktuj sie z administratowem';
            die;
        }

        return true;
    }
}