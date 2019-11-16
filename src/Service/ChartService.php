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
     * @param string $type
     * @return string
     */
    public function getDataTemperature(DateTime $start, DateTime $end, int $id, string $type): string
    {


        return json_encode($this->entityService->getTemperatureBetweenDate($start, $end, $id, $type));


    }

    /**Funkcja zwracajca tablice do generowania wyboru typu nazwa : id do wyboru miejsca umeiszczenia
     * @return array
     */
    public function getNameAndId(): array
    {
        return $this->entityService->getDeviceAndId();
    }


}