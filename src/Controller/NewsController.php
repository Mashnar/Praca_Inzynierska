<?php

namespace App\Controller;


use App\Service\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="news")
     * @return Response
     */
    public function index(NewsService $newsService): Response
    {

        return $this->render('news/news.html.twig', [
          'content'=>$newsService->getNews()
        ]);
    }
}
