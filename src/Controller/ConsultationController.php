<?php

namespace App\Controller;

use App\Service\ConsultationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsultationController extends AbstractController
{
    /**
     * @Route("/consultationRoute", name="consultation")
     * @param ConsultationService $consultationService
     * @return Response
     */
    public function index(ConsultationService $consultationService): Response
    {
        // dd($consultationService->getAllTeachersShift());

        // $consultationService->scrap();
        return $this->render('consultation/index.html.twig', [
            'consultation' => $consultationService->getAllTeachersShift()

        ]);
    }
}
