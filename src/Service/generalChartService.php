<?php


namespace App\Service;


use Exception;

class generalChartService
{


    private $entityService;


    private $device_outside;

    private $device_inside;


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
    public function getDataForCharts(): array
    {


        return [
            //wilgotnosc wewnatrz
            'hum_inside' => $this->getHumidityInside(),
            //cisnienie wewnatrz
            'press_inside' => $this->getPressureInside(),
            //temperatura w srodku
            'temp_inside' => $this->getTemperatureInside(),

            //na zewnatrz
            'temp_outside' => $this->getTemperatureOutside(),
            //zanieczyszczenie
            'pollution' => $this->getPollution()

        ];


    }


    /**
     * Funkcja zwracajca wilgotnosc wewnatrz
     * @return array
     */
    private function getHumidityInside(): array
    {
        return $this->entityService->get48LatestParams(null, 'humidity', $this->device_inside);

    }

    /**
     * Funckja zwracajca cisnienie wewnatrz
     * @return array
     */
    private function getPressureInside(): array
    {
        return $this->entityService->get48LatestParams(null, 'pressure', $this->device_inside);
    }

    /**
     * @return array
     */
    private function getTemperatureInside(): array
    {
        return $this->entityService->get48LatestParams(null, 'temperature', $this->device_inside);


    }

    /**
     * @return array
     */
    private function getTemperatureOutside(): array
    {
        return $this->entityService->get48LatestParams(null, 'temperature', $this->device_outside);


    }

    /**
     * Funkcja zwracajca zanieczyszczenie dla danych dat
     * @return array
     */
    private function getPollution(): array
    {
        //biore 8 ostatnich wpisow z parametrem false,bo nie posiadam id
        return $this->entityService->get8LatestPollution(null, false, $this->device_outside);

    }


}