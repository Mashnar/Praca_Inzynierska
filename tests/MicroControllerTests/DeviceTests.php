<?php


namespace App\Tests\MicroControllerTests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeviceTests extends WebTestCase
{

    private $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = static::createClient();

    }

    /** @test */
    public function addNewDevice(): void
    {

        $this->client->request('POST', 'api/save_device', [
            'name' => 'TEST_DEVICE',
            'ip_address' => '192.168.0.2'
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    }

    /** @depends addNewDevice
     * @test
     */
    public function updateExistDevice(): void
    {


        $this->client->request('POST', 'api/save_device', [
            'name' => 'TEST_DEVICE',
            'ip_address' => '192.169.0.3'
        ]);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    }
}