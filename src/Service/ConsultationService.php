<?php


namespace App\Service;


use App\Entity\Consultation;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ConsultationService
{
    /**
     * @var string zawartosc strony pod adresem $url_teachers
     */
    private $contentPage;
    /**
     * @var HttpClientInterface klient do pobierania contentu strony
     */
    private $httpClient;
    /**
     * @var Crawler obiekt crawlera do przeszukiwania strony
     */
    private $crawler;
    /**
     * @var array tablica z danymi do wysylki do bazy
     */
    private $dataAboutConsultation;

    private $entityService;
    /**
     * @var string adres ze wsyzstkimi pracownikami wydzialu
     */
    private $url_teachers = 'http://wfi.uni.lodz.pl/wydzial/pracownicy/';

    public function __construct(EntityService $entityService)
    {
        $this->httpClient = HttpClient::create();
        $this->crawler = new Crawler();
        $this->entityService = $entityService;
    }

    /**
     * Funkcja zwracajca wszystkie dane o wykladowcach ( z ostatniego pobrania)
     * @return array
     */
    public function getAllTeachersShift(): array
    {
        return $this->entityService->getAllTeachersShift();
    }


    /**
     * Funkcja parsująca strone
     * w array $this->>dataAbout bedziemy mieli dane o : dane kontaktowe , opis gdzie ma pokoj itp, jego dyzur oraz link
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function scrap(): void
    {


        //wykonuje request na stronie ze wszystkimi nauczycielami
        $this->makeRequest($this->url_teachers);
        //dodaje kontent do crawlera
        $this->addContentToCrawler();
        //bierzemy linki oraz nazwy
        $this->getLinkAndNamesForTeachers();
        //biore dla kazdego nauczyciela opis i jego dyzur
        $this->getShiftAndDescriptionForTeachers();
        //wysylam na koniec zeby nie byl oprzestoju
        $this->entityService->clearTable(Consultation::class);
        $this->sendToDatabase();

    }


    /**
     * @param $url
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function makeRequest($url): void
    {
        $this->contentPage = $this->httpClient->request('GET', $url)->getContent();


    }

    /**
     * Funkcja dodajaca do crawlera content strony
     */
    private function addContentToCrawler(): void
    {
        $this->crawler->add($this->contentPage);

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
            $this->addContentToCrawler();
            $this->dataAboutConsultation[$key]['shift'] = $this->setShiftPerTeacher();
            $this->dataAboutConsultation[$key]['description'] = $this->setDescriptionPerTeacher();


        }


    }


    /**
     * Funkcja czyszcząca crawler, musi byc wywolywana po kazdym pobraniu content dla 1 nauczyciela
     */
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
            if ($this->checkIsStringContainDyżur($domElement->nodeValue)) {

                //konsultacje
                return $domElement->nodeValue;
            }

        }
        return 'Nie podano';
    }

    //trzeba popratrzec nad tym , bo nie zawsze lapie, zmienimy moze na tab-id-1-container
    private function setDescriptionPerTeacher(): string
    {
        $dataCrawler = $this->crawler->filter('div[id="tab-id-1-container"]');
        foreach ($dataCrawler as $domElement) {
            if (!empty($domElement->nodeValue)) {
                return $domElement->nodeValue;
            }


        }
        return 'Nie podano';

    }

    /**Funkcja sprawdzajaca czy string ma słowo dyżur. Jesli ma, to wiemy ze są tam napisane godziny dyzuru
     * @param $str
     * @return bool
     */
    private function checkIsStringContainDyżur($str): bool
    {
        if (preg_match("/Dyżur/i", $str))
            return true;
        return false;
    }


    /**
     * @param array $data
     * @return Consultation
     */
    private function createObjectPerOneTeacher(array $data): Consultation
    {
        $teacher = new Consultation();
        $teacher->setName($data['name']);
        $teacher->setDescription($data['description']);
        $teacher->setShift($data['shift']);
        return $teacher;
    }


    /**
     *Funkcja wysylajaca do bazy wsyzstkie dane
     */
    private function sendToDatabase(): void
    {

        $this->entityService->beginTransaction();
        foreach ($this->dataAboutConsultation as $key => $value) {
//            print_r($value);
            $this->entityService->persist($this->createObjectPerOneTeacher($value));
        }
        $this->entityService->flush();
        $this->entityService->commit();

    }


}