<?php

namespace App\Controller;

use App\Service\DeviceService;
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
     * @return Response
     */
    public function temperature(Request $request): Response
    {

        $temp = new TemperatureService();
        $temp->saveTemperature($request->request->all());
        return new Response(Response::HTTP_OK);


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
        $deviceService->saveFirstAccessDevice($request->request->all());
        //Jesli nasz serwis nie zwroci zadnego błędu, zwracamy status ok
        return new Response(Response::HTTP_OK);

    }

}
