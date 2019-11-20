<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="news")
     * @param NewsCronService $newsService
     * @return Response
     */
    public function index(): Response
    {

        return $this->render('news/news.html.twig', [
//            'content'=>$newsService->newsFromWebsite()
        ]);
    }
}
