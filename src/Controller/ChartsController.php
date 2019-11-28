<?php

namespace App\Controller;


use App\Service\DetailsChartService;
use App\Service\generalChartService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartsController extends AbstractController
{

    /**
     *Funkcja wywłowyana przez JS do generowania szczeoglowych wykresow
     * @Route("/chart/{id_device}/{type}",   name="chart",  options={"expose"=true} ,defaults={"id_device"=null,"type"=null} )
     * @param DetailsChartService $chartService
     * @param int $id_device
     * @param string $type
     * @return Response
     * @throws Exception
     */

    public function chart(DetailsChartService $chartService, $id_device, $type): Response
    {

        $end = new DateTime();
        $start = (new DateTime())->modify('-2 day');


        return new JsonResponse($chartService->getData($start, $end, $id_device, $type));
    }


    /**
     *
     * Generowanie menu do wybory wykresów
     * @Route("/menuCharts", name="menuCharts")
     *
     */
    public function menuChart(): Response
    {
        return $this->render('charts/menuCharts.html.twig');
    }


    /**
     *Funkcja generujaca wykresy szczegolowe
     * @Route("/chartsDetails", name="chartsDetails")
     * @param DetailsChartService $chartService
     * @return Response
     *
     *
     */

    public function generate_chart(DetailsChartService $chartService): Response
    {

        return $this->render('charts/chartsDetails.html.twig', ['names' => $chartService->getNameAndId()]);


    }

    /**
     * Funkcja wywoluwajaa widok ogolnych wykresow
     * @Route("/generalChart", name="generalChart")
     * @return Response
     *
     *
     */
    public function generalChart(): Response
    {
        return $this->render('charts/generalChart.html.twig');
    }

    /**
     *
     *Funkcja wywoływana przez JavaScript do wziecia danych do wykresów ogolnych
     * @Route("/dataForGeneralChart",   name="dataForGeneralChart",  options={"expose"=true}  )
     * @param generalChartService $generalChartService
     * @return JsonResponse
     */
    public function dataForGeneralChart(generalChartService $generalChartService): JsonResponse
    {
        return new JsonResponse($generalChartService->getDataForCharts());
    }








}
