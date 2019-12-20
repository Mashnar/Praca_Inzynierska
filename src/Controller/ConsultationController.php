<?php

namespace App\Controller;

use App\Service\ConsultationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsultationController extends AbstractController
{
    /**
     * @Route("/consultation", name="consultation")
     * @param ConsultationService $consultationService
     * @return Response
     */
    public function index(ConsultationService $consultationService)
    {
        set_time_limit(200);
        $consultationService->scrap();
        return $this->render('consultation/index.html.twig', [

        ]);
    }
}
