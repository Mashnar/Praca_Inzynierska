<?php


namespace App\Command;

use App\Service\EntityService;
use App\Service\NewsCronService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class newsFromWebsite extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:update-categories';
    private $newsCronService;
    private $entityService;

    /**
     * newsFromWebsite constructor.
     * @param NewsCronService $newsCronService
     * @param EntityService $entityService
     */
    public function __construct(NewsCronService $newsCronService, EntityService $entityService)
    {
        parent::__construct();
        $this->entityService = $entityService;
        $this->newsCronService = $newsCronService;
    }


    protected function configure()
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
        /**
         * TODO
         * zrobić walidacje oraz rollback oraz sprawdzanie czy nei ma tego samego
         */
        // ...
        //zwracam sobie tablice w postaci
        /**
         *
         *
         *  ["news-podpiety"]=>
         * int(27)
         * ["news"]=>
         * int(14)
         * ["news-plany"]=>
         * int(67)
         * ["komentarz-ekspercki"]=>
         * int(205)
         */
        $categories = $this->newsCronService->generateIdForCategories();

        //rozpoczynam transakcje
        $this->entityService->beginTransaction();
        foreach ($categories as $key => $value) {

            $object = $this->newsCronService->createCategoryObject($key, $value);

            $this->entityService->persist($object);
        }


        $this->entityService->flush();

        $this->entityService->commit();


    }
}