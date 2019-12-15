<?php

namespace App\Controller;

use App\Service\FacebookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     *
     * Jest to controller renderujący widok głownej strony.
     */
    public function index(): Response
    {

        //Zwracam widok
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }



    /**
     * @Route("/fb", name="fb")
     * @param FacebookService $facebookService
     * @return Response
     *
     */
    public function facebook(FacebookService $facebookService): Response
    {
        dd($facebookService->get());
        return new Response();
    }



}



