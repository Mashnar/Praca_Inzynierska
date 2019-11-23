<?php


namespace App\Command;

use App\Service\CategoriesCronService;
use App\Service\EntityService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class categoriesFromWebsite extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:update-categories';
    private $categoriesCronService;
    private $entityService;

    /**
     * categoriesFromWebsite constructor.
     * @param CategoriesCronService $newsCronService
     * @param EntityService $entityService
     */
    public function __construct(CategoriesCronService $newsCronService)
    {
        parent::__construct();

        $this->categoriesCronService = $newsCronService;
    }


    protected function configure(): void
    {
        // ...
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Pobiera kategorie ze strony')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Polecenie będzie uruchamiane co 6 godzin oraz przy starcie systemu');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->categoriesCronService->downloadAndSaveCategories();

    }
}