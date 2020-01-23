<?php


namespace App\Command;


use App\Service\EntityService;
use App\Service\ValidationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class addDescription extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:add-description';
    private $entityService;
    private $validationService;


    public function __construct(EntityService $entityService, ValidationService $validationService)
    {
        parent::__construct();
        $this->entityService = $entityService;
        $this->validationService = $validationService;
    }

    protected function configure(): void
    {
        // ...
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Dodaje opis do urzadzen.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('1 parametr urzadzenie wewnetrzne, drugi zewnetrzne')
            ->addArgument('outside', InputArgument::REQUIRED, 'Opis urzadzenia zewnetrznego')
            ->addArgument('inside', InputArgument::REQUIRED, 'Opis urzadzenia wewnetrznego');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // pobieram urzadzenia
        $outside = $this->entityService->getDeviceByName('SDS_OUTSIDE');
        $inside = $this->entityService->getDeviceByName('SDS_INSIDE');
        //ustawiam argumenty na opis
        $outside->setDescription($input->getArgument('outside'));
        $inside->setDescription($input->getArgument('inside'));
        //waliduje
        $this->validationService->validate($outside);
        $this->validationService->validate($inside);
        //wysylam jak jest ok
        $this->entityService->persist($outside);
        $this->entityService->persist($inside);
        $this->entityService->flush();

        return $output->write('Done');


    }


}