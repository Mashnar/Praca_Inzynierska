<?php


namespace App\Service;


class NewsService
{

    /**
     * @var entityService
     */
    private $entityService;


    /**
     * NewsService constructor.
     * @param EntityService $entityService
     */
    public function __construct(EntityService $entityService)
    {
        $this->entityService = $entityService;
    }


    /**
     * Funkcja wywoływana przez kontroler służaca do sciagania newsów
     * @return array
     */
    public function getNews():array
    {


        return $this->queryNews();


    }


    /**
     * Funckja pytajaca sie entity o wszystkie newsy
     * @return \App\Entity\CategoriesWebsite[]|\App\Entity\WebsitePosts|\App\Entity\WebsitePosts[]|object[]
     */
    private function queryNews()
    {
        return $this->entityService->getNews();


    }


}