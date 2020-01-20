<?php


namespace App\Service;

use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Forecast;
use DateTimezone;
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
    private $owm;
    private $apiKEY = '68e997e13515cf96431962052d6b202e';
    //obiekt z danymi jak przypiszemy miasto
    private $objData;
    private $city = 'Lodz';
    private $unit = 'metric';
    private $language = 'pl';
    private $days = 5;
    private $days_to_query;
    private $entityservice;
    /**
     * @var array z danymi do przekazania do widoku
     */
    private $dataToSend;

    public function __construct(EntityService $entityService)
    {


        $this->owm = new OpenWeatherMap($this->apiKEY, GuzzleAdapter::createWithConfig([]), new RequestFactory());
        //https://stackoverflow.com/a/8308005
        //deklaruje 5 kolejnych dni
        $this->days_to_query = [
            date('d.m.y', strtotime('+1 days')) . ' 12:00',
            date('d.m.y', strtotime('+2 days')) . ' 12:00',
            date('d.m.y', strtotime('+3 days')) . ' 12:00',
            date('d.m.y', strtotime('+4 days')) . ' 12:00',
        ];

        $this->entityservice = $entityService;


    }

    //https://github.com/cmfcmf/OpenWeatherMap-PHP-API/blob/master/Examples/CurrentWeather.php

    /**
     * Funkcja zwracaja dane w postaci tablicy z 5 dni
     * @return array
     * @throws OpenWeatherMap\Exception
     */
    public function getData(): array
    {

        $this->setWeatherToday();

        //dzisiejsza pogoda
        $this->generateArrayToSendDaily($this->objData);


        //zrobimy ze z kazdego dnia do przodu o 5 bierzemy od godziny 12 do 15 temperatura i te dane bedziemy wyswietlac
        $this->setWeatherForecast();
        foreach ($this->objData as $key => $weather) {
            foreach ($this->days_to_query as $value) {
                if ($weather->time->from->format('d.m.y H:i') === $value) {

                    $this->generateArrayToSend($weather);

                }


            }


        }
        return $this->dataToSend;

    }


    /**
     * Funkcja zwracajca parametry wewnatrz budynku ( będzie to ... narazie mała aula ( kontroler SDS_TEMP_4)
     * @return array
     */
    public function getDataInside(): array
    {
        return $this->entityservice->getNewestWeatherParameter('SDS_TEMP_4')[0];

    }

    /**
     * Funckja zwracaja parametry na zewnatrz budynku ( SDS_TEMP_1)
     * @return array
     */
    public function getDataOutside(): array
    {
        //biore parametry pogodewe i odrazu 0 element
        $weatherParameter = $this->entityservice->getNewestWeatherParameter('SDS_TEMP_1')[0];
        $pollution_parameter = $this->entityservice->getXLatestPollution
        (null, false, $this->entityservice->getDeviceByName('SDS_TEMP_1')
            , 1)[0];
        $pollution_parameter['createdAtPollution'] = $pollution_parameter['createdAt'];
        unset($pollution_parameter['createdAt']);
        return array_merge($weatherParameter, $pollution_parameter);


    }


    /**
     * Tworze obiekt uzywany do zapytan o pogode
     * @throws OpenWeatherMap\Exception
     */
    private function setWeatherForecast(): void
    {
        $this->objData = $this->owm->getWeatherForecast($this->city, $this->unit, $this->language, '', $this->days);
    }

    /**
     * Funckja tworzaca tablice z danymi do outputu
     * @param Forecast $weather
     * @return void
     */
    private function generateArrayToSend(Forecast $weather): void
    {
        $this->dataToSend[$weather->time->from->setTimezone(new DateTimezone('Europe/Berlin'))->format('d.m.y')] = [
            'temp' => $weather->temperature->getFormatted(),
            'clouds' => $weather->clouds->getDescription() . ' (' . $weather->clouds . ')',
            'icon' => $weather->weather->getIconUrl(),
            'wind_speed' => $weather->wind->speed->getFormatted(),
            'direction' => $this->convertDegree($weather->wind->direction->getValue()),
            'precipitation' => $weather->precipitation->getValue(),
        ];
    }


    /**
     * @param OpenWeatherMap\CurrentWeather $weather
     */
    private function generateArrayToSendDaily(OpenWeatherMap\CurrentWeather $weather): void
    {
//https://github.com/cmfcmf/OpenWeatherMap-PHP-Api/issues/73#issuecomment-211013246
        $this->dataToSend[$weather->lastUpdate->setTimezone(new DateTimezone('Europe/Berlin'))->format('d.m.y')] = [
            'temp' => $weather->temperature->getFormatted(),
            'clouds' => $weather->clouds->getDescription() . ' (' . $weather->clouds . ')',
            'icon' => $weather->weather->getIconUrl(),
            'wind_speed' => $weather->wind->speed->getFormatted(),
            'direction' => $this->convertDegree($weather->wind->direction->getValue()),
            'precipitation' => $weather->precipitation->getValue()
        ];
    }


    /**
     *Funkcja ustawiajaca obiekt na pobieranie dzisiejszej pogody
     */
    private function setWeatherToday(): void
    {
        try {
            $this->objData = $this->owm->getWeather($this->city, $this->unit, $this->language, '');
        } catch (OpenWeatherMap\Exception $e) {
        }

    }


    /**
     * Funkcja przerabiajaca stopnie na kierunek wiatru
     * https://stackoverflow.com/a/36475516
     * @param float $degree
     * @return string|null
     */
    private function convertDegree(float $degree): ?string
    {
        if ($degree > 337.5) {
            return 'Północny';
        }
        if ($degree > 292.5) {
            return 'Północno Zachodni';
        }
        if ($degree > 247.5) {
            return 'Zachodni';
        }
        if ($degree > 202.5) {
            return 'Południowo Zachodni';
        }
        if ($degree > 157.5) {
            return 'Południowy';
        }
        if ($degree > 122.5) {
            return 'Południowo Wschodni';
        }
        if ($degree > 67.5) {
            return 'Wschodni';
        }
        if ($degree > 22.5) {
            return 'Północno Wschodni';
        }
        return 'Północny';
    }


}