<?php


namespace App\Tests\RouteTest;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class RoutingTest extends WebTestCase
{


    /**
     * @dataProvider provideUrls
     * @test
     * @param $url
     */
    public function testPageIsSuccessful($url): void
    {
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }


    public function provideUrls()
    {


        return [
            ['/'],
            ['/menuCharts'],
            ['/chartsDetails'],
            ['/generalChart'],
            ['/newsRoute'],
            ['/chart'],
            ['/scheduleRoute'],
            ['/weatherRoute'],
            ['/consultationRoute'],
            ['/buildingRoute'],
            ['/dataForGeneralChartInside'],
            ['/dataForGeneralChartOutside']
            // ...
        ];
    }
}