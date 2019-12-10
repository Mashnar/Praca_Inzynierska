<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WeatherAPIController extends AbstractController
{
    /**
     * @Route("/weather", name="weather")
     * @param WeatherService $weatherService
     */
    public function index(WeatherService $weatherService)
    {
        $weatherService->test();
        return $this->render('weather_api/index.html.twig', [

        ]);
    }
}
