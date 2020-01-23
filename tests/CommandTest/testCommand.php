<?php


namespace App\Tests\CommandTest;


use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class testCommand extends KernelTestCase
{
    private $application;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->application = new Application(static::createKernel());
    }

    /**
     * @test
     */
    public function addDescriptionTest(): void
    {


        $command = $this->application->find('app:add-description');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'outside' => 'test_outside',
            'inside' => 'test_inside'


        ]);


        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Done', $output);


    }

    /**
     * @test
     */
    public function updateCategoriesTest(): void
    {
        $command = $this->application->find('app:update-categories');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);


        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Done', $output);
    }

    /**
     * @depends updateCategoriesTest
     * @test
     */
    public function downloadNewsTest(): void
    {
        $command = $this->application->find('app:download-news');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);


        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Done', $output);
    }

    /**
     * @test
     */
    public function downloadShiftTest(): void
    {
        $command = $this->application->find('app:teacher-shift');

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);


        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Done', $output);
    }
}