<?php

namespace App\Controller;


use App\Service\FacebookService;
use App\Service\NewsService;
use Facebook\Exceptions\FacebookSDKException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/newsRoute", name="news")
     * @param NewsService $newsService
     * @param FacebookService $facebookService
     * @return Response
     * @throws FacebookSDKException
     */
    public function index(NewsService $newsService, FacebookService $facebookService): Response
    {

        return $this->render('news/news.html.twig', [
            'content' => $newsService->getNews(),
            'facebook' => $facebookService->getFacebookNews()
        ]);
    }
}
