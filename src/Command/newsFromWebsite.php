<?php


namespace App\Command;


use App\Service\NewsCronService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class newsFromWebsite extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:download-news';

    private $newsCronService;


    public function __construct(NewsCronService $newsCronService)
    {
        parent::__construct();
        $this->newsCronService = $newsCronService;
    }

    protected function configure(): void
    {
        // ...
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Pobiera newsy ze strony')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Polecenie będzie uruchamiane co 6 godzin oraz przy starcie systemu');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->newsCronService->downloadAndSaveNews();
        return $output->write('Done');
    }







}