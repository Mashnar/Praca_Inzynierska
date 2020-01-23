<?php


namespace App\Tests\MicroControllerTests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TempAndPollutionTests extends WebTestCase
{
    private $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = static::createClient();
    }

    /**
     * @test
     */
    public function addTemperature(): void
    {

        $this->client->request('POST', 'api/save_temperature', [
            'temperature' => '22.3',
            'pressure' => '988.41',
            'humidity' => '60.4',
            'device_name' => 'TEST_DEVICE'
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     */
    public function addPollution(): void
    {


        $this->client->request('POST', 'api/save_pollution', [
            'pm25' => '20.3',
            'pm10' => '30.41',
            'device_name' => 'TEST_DEVICE'
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}