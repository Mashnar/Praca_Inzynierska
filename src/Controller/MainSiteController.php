<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainSiteController extends AbstractController
{
    /**
     * @Route("/main/site", name="main_site")
     */
    public function index()
    {
        return $this->render('main_site/index.html.twig', [
            'controller_name' => 'MainSiteController',
        ]);
    }
}
