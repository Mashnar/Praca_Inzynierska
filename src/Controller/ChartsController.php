<?php

namespace App\Controller;


use App\Entity\Device;
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
        //jesli jest nullem
        $end = new DateTime();
        $start = (new DateTime())->modify('-1 day');
        //ustawiam domyslnie jaki wykres
        if ($id_device === null) {
            $id_device = $this->getDoctrine()->getRepository(Device::class)->getDeviceId('SDS_TEMP_4');
        }
        if ($type === null) {
            $type = 'temperature';
        }


        return new JsonResponse($chartService->getDataTemperature($start, $end, $id_device, $type));
    }


    /**
     *
     * @Route("/charts", name="charts")
     * @param ChartService $chartService
     * @return Response
     *
     *
     */

    public function generate_chart(ChartService $chartService): Response
    {

        return $this->render('charts/charts.html.twig', ['names' => $chartService->getNameAndId()]);


    }
}
