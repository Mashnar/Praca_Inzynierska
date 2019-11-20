<?php


namespace App\Service;


use DateTime;

class ChartService
{

    /**@var $entityService */
    private $entityService;


    /**
     * ChartService constructor.
     * @param EntityService $entityService
     */
    public function __construct(EntityService $entityService)
    {
        $this->entityService = $entityService;

    }


    /**
     * Funkcja zwracajaca dane o temperaturze pomiedzy dwoma datami
     * @param DateTime $start
     * @param DateTime $end
     * @param int $id
     * @param string $type domyslnie temperatura
     * @return string
     */
    public function getDataTemperature(DateTime $start, DateTime $end, $id, $type)
    {
        if ($id === null) {
            $id = $this->entityService->getIdByName('SDS_TEMP_4');
        }


        if ($type === null) {
            $type = 'temperature';
        }
        $data = $this->entityService->getTemperatureBetweenDate($start, $end, $id, $type);


        //jesli dane sa puste, to zwracamy ostatnie 24
        if (empty($data)) {
            return json_encode($this->entityService->get24LatestParams($id, $type));
        }

        return json_encode($data);


    }

    /**Funkcja zwracajca tablice nazwe oraz id urzadzen dostepnych w serwisie
     * @return array
     */
    public function getNameAndId(): array
    {
        return $this->entityService->getDeviceAndId();
    }


}