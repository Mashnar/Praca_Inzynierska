<?php

namespace App\Controller;

use App\Service\DeviceService;
use App\Service\PollutionService;
use App\Service\TemperatureService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MicroControllerController extends AbstractController
{
    /**
     * @Route("/api/save_temperature", name="save_temperature")
     * @param Request $request
     * @param TemperatureService $temperatureService
     * @return Response
     */
    public function temperature(Request $request, TemperatureService $temperatureService): Response
    {

        return $temperatureService->saveTemperature($request->request->all());



    }


    /**
     * Funkcja służąca do zapisu urządzenia w bazie danych (adres ip, nazwa)
     * @param Request $request
     * @param DeviceService $deviceService
     * @return Response
     * @Route("/api/save_device", name="save_device")
     * @throws Exception
     */
    public function device(Request $request, DeviceService $deviceService): Response
    {

        return $deviceService->saveFirstAccessDevice($request->request->all());
        //Jesli nasz serwis nie zwroci zadnego błędu, zwracamy status ok


    }


    /** Funkcja sluząca do zapisu danych o pyle do bazy danych(pm10,pm25)
     * @param Request $request
     * @param PollutionService $pollutionService
     * @Route("/api/save_pollution", name="save_pollution")
     * @return Response
     */
    public function pollution(Request $request, PollutionService $pollutionService): Response
    {
        return $pollutionService->savePollution($request->request->all());
    }

}
