<?php

namespace App\Controller;


use App\Service\ChartService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartsController extends AbstractController
{

    /**
     *
     * @Route("/chart/{id_device}/{type}",   name="chart",  options={"expose"=true} ,defaults={"id_device"=null,"type"=null} )
     * @param ChartService $chartService
     * @param int $id_device
     * @param string $type
     * @return Response
     * @throws Exception
     */

    public function chart(ChartService $chartService, $id_device, $type): Response
    {

        $end = new DateTime();
        $start = (new DateTime())->modify('-1 day');


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
     *
     * @Route("/chartsDetails", name="chartsDetails")
     * @param ChartService $chartService
     * @return Response
     *
     *
     */

    public function generate_chart(ChartService $chartService): Response
    {

        return $this->render('charts/chartsDetails.html.twig', ['names' => $chartService->getNameAndId()]);


    }

    /**
     *
     * @Route("/generalChart", name="generalChart")
     * @return Response
     *
     *
     */
    public function generalChart(): Response
    {
        return $this->render('charts/generalChart.html.twig');
    }







}
