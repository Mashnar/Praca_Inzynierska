<?php


namespace App\Service;


use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ConsultationService
{
    /**
     * @var string zawartosc strony pod adresem $url_teachers
     */
    private $contentPage;

    private $httpClient;

    private $crawler;

    private $dataAboutConsultation;

    /**
     * @var string adres ze wsyzstkimi pracownikami wydzialu
     */
    private $url_teachers = 'http://wfi.uni.lodz.pl/wydzial/pracownicy/';

    public function __construct()
    {
        $this->httpClient = HttpClient::create();
        $this->crawler = new Crawler();
    }


    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function scrap(): void
    {
//        $this->testForURL();
        //wykonuje request na stronie ze wszystkimi nauczycielami
        $this->makeRequest($this->url_teachers);
        //dodaje kontent do crawlera
        $this->addContentToCrawler($this->contentPage);
        //bierzemy linki oraz nazwy
        $this->getLinkAndNamesForTeachers();

        $this->getShiftAndDescriptionForTeachers();

    }


    /**
     * @param $url
     * @param $typeContent
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    private function makeRequest($url): void
    {
        $this->contentPage = $this->httpClient->request('GET', $url)->getContent();


    }

    /**
     * @param $content
     */
    private function addContentToCrawler($content): void
    {
        $this->crawler->add($content);

    }

    private function getLinkAndNamesForTeachers(): void
    {

        $dataCrawler = $this->crawler->filter('.rpwe-title');

        foreach ($dataCrawler as $domElement) {


            $this->dataAboutConsultation[] =
                ['name' => $domElement->nodeValue,
                    'link' => $domElement->lastChild->getAttribute('href')];
        }
    }

    private function getShiftAndDescriptionForTeachers(): void
    {
        foreach ($this->dataAboutConsultation as $key => $teacher) {
            $this->clearCrawler();

            //tworze zapytanie na url
            $this->makeRequest($teacher['link']);

            //przekazuje content
            $this->addContentToCrawler($this->contentPage);
            $this->dataAboutConsultation[$key]['shift'] = $this->setShiftPerTeacher();
            $this->dataAboutConsultation[$key]['description'] = $this->setDescriptionPerTeacher();
            echo '<pre>';
            print_r($this->dataAboutConsultation[$key]);

            echo '</pre>';


        }


    }


//    private function testForURL(): void
//    {
//        $temp = $this->httpClient->request('GET', 'http://wfi.uni.lodz.pl/borowski-norbert-mgr-inz')->getContent();
//        $this->crawler->add($temp);
//        $dataCrawler = $this->crawler->filter('.eachInfo');
//        foreach ($dataCrawler as $domElement) {
//            //wszystkie dane
//            //dd($domElement->nodeValue);
//        }
//
//
//
//    }

    private function clearCrawler(): void
    {
        $this->crawler->clear();
    }

    /**
     *
     */
    private function setShiftPerTeacher(): string
    {
        $datasecond = $this->crawler->filter('div[id="tab-id-2-container"]');
        foreach ($datasecond as $domElement) {
            if (preg_match("/Dyżur/i", $domElement->nodeValue)) {

                //konsultacje
                return $domElement->nodeValue;
            }

        }
        return 'Nie podano';
    }

    //trzeba popratrzec nad tym , bo nie zawsze lapie, zmienimy moze na tab-id-1-container
    private function setDescriptionPerTeacher(): string
    {
        $dataCrawler = $this->crawler->filter('.eachInfo');
        foreach ($dataCrawler as $domElement) {
            if (!empty($domElement->nodeValue)) {
                return $domElement->nodeValue;
            }


        }
        return 'Nie podano';

    }


//    private function checkIs
}