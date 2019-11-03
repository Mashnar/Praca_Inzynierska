<?php

namespace App\Controller;

use App\Service\FacebookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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


        return new Response();
    }

    /**
     * @Route("/wp", name="wp")
     * @param FacebookService $facebookService
     * @return Response
     *
     * @throws TransportExceptionInterface
     */
    public function wp(): Response
    {
        $client = HttpClient::create();
        try {
            $response = $client->request('GET', 'http://wfi.uni.lodz.pl/wp-json/wp/v2/posts?&per_page=10');
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }


        return New Response();

    }


}



