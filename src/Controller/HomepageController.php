<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
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
     *
     * @Route("/scrap", name="scrap")
     *
     * @throws TransportExceptionInterface
     */
    public function scrap()
    {
        $client = HttpClient::create();
        try {
            $response = $client->request('GET', 'http://wfi.uni.lodz.pl/plany/stacjonarne/informatyka/1-stopnia/');
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }


        try {
            $crawler = new Crawler($response->getContent());
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }

        $crawler->filter('a')->each(function (Crawler $node) {
            echo '<br>';
            //tutaj bysmy regexa walneli na wsyzstkie z pdf i bysmy mieli nazwy plików
            echo $node->attr('href');
        });


        return new Response();
    }


}
