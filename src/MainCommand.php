<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MainCommand extends Command
{
    private const ARG_INPUT_FILE = 'inputFile';

    protected static $defaultName = 'task_separator';

    protected function configure(): void
    {
        $this
            ->setDescription(
                "Task separator.\n".
                "  App separates tasks from the input JSON file to the output files:\n".
                "  - inspections.json - inspection tasks\n".
                "  - accidents.json   - accident tasks\n".
                "  - unknown.json     - tasks with incorrect data"
            )
            ->addArgument(self::ARG_INPUT_FILE, InputArgument::REQUIRED, 'Input JSON file with the list of tasks')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->showHeader($io);

        if ($inputFile = $input->getArgument(self::ARG_INPUT_FILE)) {
            $io->note(sprintf('You passed an argument: %s', $inputFile));
        }

        return Command::SUCCESS;
    }

    private function showHeader(SymfonyStyle $io): void
    {
        $io->writeln($this->getApplication()->getName() . ' ' . $this->getApplication()->getVersion());
    }
}
