<?php


namespace App\Service;


use Exception;

class generalChartService
{


    private $entityService;


    private $device_outside;

    private $device_inside;

    /**
     * @var boolean true or false , true w srodku false na zewnatrz
     */
    private $placed;


    public function __construct(EntityService $entityService)
    {
        $this->entityService = $entityService;
        $this->device_inside = $this->entityService->getDeviceByName('SDS_TEMP_4');
        $this->device_outside = $this->entityService->getDeviceByName('SDS_TEMP_1');

    }


    /**Funkcja generujaca dane dla wykresów
     * Tablica będzie maila 4 elementy dla kazdego wykresu
     * pollution,temperature,humidity,pressure
     * @return array
     *
     * @throws Exception
     */
    public function getDataForChartsInside(): array
    {

        $this->placed = true;

        return [
            //wilgotnosc wewnatrz
            'hum_inside' => $this->getHumidity(),
            //cisnienie wewnatrz
            'press_inside' => $this->getPressure(),
            //temperatura w srodku
            'temp_inside' => $this->getTemperature(),
            //zanieczyszczenie
            'pollution' => $this->getPollution()

        ];


    }


    public function getDataForChartsOutside(): array
    {
        $this->placed = false;

        return [
            //wilgotnosc wewnatrz
            'hum_outside' => $this->getHumidity(),
            //cisnienie wewnatrz
            'press_outside' => $this->getPressure(),
            //temperatura w srodku
            'temp_outside' => $this->getTemperature(),
            //zanieczyszczenie
            'pollution' => $this->getPollution()

        ];

    }




    /**
     * Funkcja zwracajca wilgotnosc wewnatrz
     * @return array
     */
    private function getHumidity(): array
    {
        return $this->entityService->get48Or24LatestParams(null, 'humidity',
            ($this->placed ? $this->device_inside : $this->device_outside), 24);

    }

    /**
     * Funckja zwracajca cisnienie wewnatrz
     * @return array
     */
    private function getPressure(): array
    {
        return $this->entityService->get48Or24LatestParams(null, 'pressure',
            ($this->placed ? $this->device_inside : $this->device_outside), 24);
    }

    /**
     * @return array
     */
    private function getTemperature(): array
    {
        return $this->entityService->get48Or24LatestParams(null, 'temperature',
            ($this->placed ? $this->device_inside : $this->device_outside), 24);


    }


    /**
     * Funkcja zwracajca zanieczyszczenie dla danych dat
     * @return array
     */
    private function getPollution(): array
    {
        //biore 8 ostatnich wpisow z parametrem false,bo nie posiadam id
        return $this->entityService->getXLatestPollution(null, false, $this->device_outside,8);

    }


}