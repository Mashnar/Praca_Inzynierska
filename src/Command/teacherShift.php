<?php


namespace App\Command;


use App\Service\ConsultationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class teacherShift extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:teacher-shift';
    private $consultationService;

    public function __construct(string $name = null, ConsultationService $consultationService)
    {
        $this->consultationService = $consultationService;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('dyzury ze strony wydzialowej')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Pobiera wszystkie dyzury wydzialowe, wykonuje sie okolo 2 minut.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
        set_time_limit(200);
        $this->consultationService->scrap();

        set_time_limit(60);


        return $output->write('Done');
    }
}