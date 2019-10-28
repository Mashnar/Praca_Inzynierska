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
     */
    public function getDataTemperature(DateTime $start, DateTime $end): string
    {


        return json_encode($this->entityService->getTemperatureBeetweenDate($start, $end));


    }

}