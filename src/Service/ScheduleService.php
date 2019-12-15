<?php


namespace App\Service;


use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ScheduleService
{

    /**
     * @var array
     * Prywatna tablica z adresami do stron z plikami z planów zajęc
     */
    private $url = [
        'Informatyka I stopień' => 'http://wfi.uni.lodz.pl/plany/stacjonarne/informatyka/1-stopnia/',
        'Informatyka II stopień' => 'http://wfi.uni.lodz.pl/plany/stacjonarne/informatyka/2-stopnia/',
        'Fizyka I Stopień' => 'http://wfi.uni.lodz.pl/plany/stacjonarne/fizyka/1-stopnia/',
        'Fizyka II Stopień' => 'http://wfi.uni.lodz.pl/plany/stacjonarne/fizyka/2-stopnia/',
    ];
    /**
     * URL do niestandardowych, wydzialm go bo tam moze byc wiecej niz  6 i zeby nie wygladalo brzydko bede bral tylko
     * najnowsze, poniewaz nie ma sensu wyswietlac wszystkich
     * @var string
     */
    private $url_special = 'http://wfi.uni.lodz.pl/plany/niestacjonarne/informatyka/';

    /**
     * Wyłaczenie toolbara i navbarów ( zeby nie drukować)
     * @var string
     */
    private $options = '#toolbar=0&navpanes=0&scrollbar=0';

    public function scrap(): array
    {
        $content = [];


        foreach ($this->url as $key => $value) {
            $crawler = $this->createScraper($this->generateContentWebsite($value));
            $content[$key] = $this->iterateCrawler($crawler, $value);
        }
        //generujemy content dla specjalnego URL z niestancjonarnymi studiami
        $crawler = $this->createScraper($this->generateContentWebsite($this->url_special));
        $content['Inf Niestac'] = $this->iterateCrawler($crawler, $this->url_special, true);
        return $content;

    }

    /**
     * Funkcja tworząca obiekty typu crawler
     * @param $response
     * @return Crawler
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function createScraper($response): Crawler
    {
        try {
            $crawler = new Crawler($response->getContent());
        } catch (ClientExceptionInterface $e) {
            throw $e;
        } catch (RedirectionExceptionInterface $e) {
            throw $e;
        } catch (ServerExceptionInterface $e) {
            throw $e;
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }


        return $crawler;
    }

    /**
     * Funkcja zwracająca cała zawartośc strony
     * @param $url
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    private function generateContentWebsite($url): ResponseInterface
    {
        $client = HttpClient::create();
        try {
            $response = $client->request('GET', $url);
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }

        return $response;
    }

    /**
     * Funkcja iterujaca crawler, chodzi po wsyztkich elementach zawierajacych znacznik html a
     * @param $crawler
     * @param $url
     * @param bool $special domyslnie false, jesli true to znaczy ze mamy specjalne filtrowanie
     * @return array
     */
    private function iterateCrawler($crawler, $url, $special = false): array
    {
        $content = [];

        //iteruje po kazdym elemencie a
        $crawler->filter('a')->each(function (Crawler $node) use (&$content, &$url) {


            if ($this->checkRegex($node->text())) {

                $name = $this->convertFilename($node->text());
                //jesli ma pdf to dodaje jeszcze adres url na poczatku aby miec ładny adres do wyswietlania juz bezposrtednio na stronie
                $content[] = [
                    'url' => $url . $node->text() . $this->options,
                    'name' => $name,
                    'order' => $this->order($name)
                ];

            }


        });

        return $this->sortingAsc($content, $special);
    }


    /**
     * Funkcja sprawdzajaca czy dany tekst ze strony ma w sobie rozszerzenie .pdf
     * @param $text
     * @return bool
     */
    private function checkRegex($text): bool
    {
        //https://stackoverflow.com/a/31125437
        $reg = "/\.pdf$/i";
        if (preg_match($reg, $text)) {
            return true;

        }
        return false;
    }

    /**
     * Funkcja konwertujaca nazwe pliku.
     * @param string $filename
     * @return false|string
     */
    private function convertFilename(string $filename): string
    {

        return $this->deleteUnderScore(strstr($filename, '.', true));

    }

    /**
     * Funkcja zamieniajca deski na spacje
     * @param string $text
     * @return string
     */
    private function deleteUnderScore(string $text): string
    {
        return str_replace('_', ' ', $text);
    }

    /**
     * Funkcja sortująca rosnąco elementy tablicy, aby nie mieć nie pokolei planów podczas wybierania
     * @param array $content
     * @param $special bool true|false , jesli true to musimy czyscic tablice
     * @return array
     */
    private function sortingAsc(array $content, $special): array
    {

        //https://stackoverflow.com/a/51174923
        $keys = array_column($content, 'order');

        array_multisort($keys, SORT_ASC, $content);
        if ($special) {
            return $this->clearArrayForOnly6Elements($content);
        }
        return $content;


    }


    /**Funkcja tworząca ordering ( bierze 1 liczbe jaka napotka z nazwy pliku)
     * @param string $str
     * @return int
     */
    private function order(string $str): int
    {
        // https://stackoverflow.com/a/12582416
        //robie abs zeby nie bral minusa3
        return abs((int)filter_var($str, FILTER_SANITIZE_NUMBER_INT));
    }


    /**Funkcja czyszczaca tablice tak ,a by bylo tylko 6 elementow ( ze wzgledu na umieszczenie na front-endzie0
     * Zawsze będzie 0 element ( lista keidy sa zjazdy oraz 5 zjadow liczonych od tyłu)
     * @param $content
     * @return array
     */
    private function clearArrayForOnly6Elements(array $content): array
    {

        if (count($content) > 6) {
            //przypisujemy zmienna tymczasowoa na url do listy zjazdow
            $url = $content[0];
            return $this->manipulateArray($content, $url);

        }
        return $content;
    }

    /**
     * Funkcja manipuljaca tablica aby wziac 5 najnowszych elementow
     * @param array $array tablica z elementami
     * @param array $url pierwszy adres
     * @return array tablica juz posorotwana i odwrocona
     *
     */
    private function manipulateArray(array $array, array $url): array
    {


        array_shift($array);
        //odwracamy tablice
        $reverse = array_reverse($array);
        //zostawiamy 5 elementow poczatkowych
        $reverse = array_slice($reverse, 0, 5);
        //znow odwracamy
        $reverse = array_reverse($reverse);
        //dodajemy 1 element
        array_unshift($reverse, $url);

        return $reverse;
    }


}