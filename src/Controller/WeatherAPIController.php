<?php

namespace App\Controller;

use App\Service\WeatherService;
use Cmfcmf\OpenWeatherMap\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherAPIController extends AbstractController
{
    /**
     * @Route("/weather", name="weather")
     * @param WeatherService $weatherService
     * @return Response
     * @throws Exception
     */
    public function index(WeatherService $weatherService): Response
    {


        return $this->render('weather_api/index.html.twig', [
            'content' => $weatherService->getData(),
            'inside' => $weatherService->getDataInside(),
            'outside'=>$weatherService->getDataOutside()


        ]);
    }
}
