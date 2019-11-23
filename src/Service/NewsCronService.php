<?php


namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsCronService
{
    /**
     * adres REST API dla strony wydziałowej
     * @var string
     */
    private $url = 'http://wfi.uni.lodz.pl/wp-json/wp/v2/';

    /**
     * @var HttpClientInterface obiekt klasy HttpClientInterface do pobierania danych ze stron
     */
    private $httpclient;


    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpclient = $httpClient;

    }

}