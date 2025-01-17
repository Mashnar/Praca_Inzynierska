<?php


namespace App\Service;


use DateTime;

class DetailsChartService
{

    /**@var $entityService */
    private $entityService;


    /**
     * DetailsChartService constructor.
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
    public function getData(DateTime $start, DateTime $end, $id, $type): string
    {
        //jesli id jest null, t bierzemy o nazwei SDS_INSIDE
        if ($id === null) {
            $id = $this->entityService->getIdByName('SDS_INSIDE');
        }

        //domyslnie temperatura
        if ($type === null) {
            $type = 'temperature';
        }
        //jesli parametr to pollution, to zwracamy za pomoca innego zapytania
        if ($type === 'pollution') {


                return json_encode($this->entityService->getXLatestPollution($id,true,null,8));


        }




        //jesli dane sa puste, to zwracamy ostatnie 24

        return json_encode($this->entityService->get48Or24LatestParams($id, $type));



    }

    /**Funkcja zwracajca tablice nazwe oraz id urzadzen dostepnych w serwisie
     * @return array
     */
    public function getNameAndId(): array
    {
        return $this->entityService->getDeviceAndId();
    }


}