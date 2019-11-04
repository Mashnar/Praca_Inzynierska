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
     * @Route("/chart", name="chart",  options={"expose"=true} )
     * @param ChartService $chartService
     * @return Response
     *
     *
     * @throws Exception
     */

    public function chart(ChartService $chartService): Response
    {
        $start = new DateTime('2019-10-23');

        $end = new DateTime('2019-10-24');


        return new JsonResponse($chartService->getDataTemperature($start, $end));
    }


    /**
     *
     * @Route("/charts", name="charts")
     * @return Response
     *
     *
     * @throws Exception
     */

    public function generate_chart(): Response
    {

        return $this->render('charts/charts.html.twig');


    }
}
