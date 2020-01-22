<?php

namespace App\Controller;

use App\Service\ScheduleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends AbstractController
{
    /**
     *
     * @Route("/scheduleRoute", name="scheduleRoute")
     * @param ScheduleService $scheduleService
     * @return Response
     */
    public function schedule(ScheduleService $scheduleService): Response
    {

        return $this->render('schedule/schedule.html.twig',

            ['url' => $scheduleService->scrap()]);
    }
}
