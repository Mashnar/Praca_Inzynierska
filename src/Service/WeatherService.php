<?php


namespace App\Service;

use Cmfcmf\OpenWeatherMap;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Factory\Guzzle\RequestFactory;

class WeatherService
{

    //https://packagist.org/packages/php-http/guzzle6-adapter
    //https://packagist.org/packages/http-interop/http-factory-guzzle
    //https://packagist.org/packages/cmfcmf/openweathermap-php-api
    /**
     * @var RequestFactory Do openweathermap
     */
    private $requestFactory;
    private $httpClient;
    private $owm;
    private $apiKEY = '68e997e13515cf96431962052d6b202e';
    //obiekt z danymi jak przypiszemy miasto
    private $objData;

    public function __construct()
    {

        $this->requestFactory = new RequestFactory();

        $this->httpClient = GuzzleAdapter::createWithConfig([]);
        $this->owm = new OpenWeatherMap('68e997e13515cf96431962052d6b202e', $this->httpClient, $this->requestFactory);

    }

    //https://github.com/cmfcmf/OpenWeatherMap-PHP-API/blob/master/Examples/CurrentWeather.php
    public function test()
    {
        $this->objData = $this->owm->getWeatherForecast('Lodz', 'metric', 'pl', '', 5);


//
//
//        foreach (  $this->objData as $key=>$weather) {
//
//            echo $key;
//                print_r($weather);
//            // Each $weather contains a Cmfcmf\ForecastWeather object which is almost the same as the Cmfcmf\Weather object.
//            // Take a look into 'Examples_Current.php' to see the available options.
//            echo "Weather forecast at " . $weather->time->day->format('d.m.Y') . " from " . $weather->time->from->format('H:i') . " to " . $weather->time->to->format('H:i');
//            echo "<br />\n";
//            echo $weather->temperature;
//            echo "<br />\n";
//            echo "Sun rise: " . $weather->sun->rise->format('d.m.Y H:i (e)');
//            echo "<br />\n";
//            echo "---";
//            echo "<br />\n";
//        }
//       // dd($obj->weather->getIconUrl());

    }
}