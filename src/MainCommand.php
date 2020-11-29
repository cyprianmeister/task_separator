<?php

namespace App;

use App\Common\Model\Enum\Format;
use App\Common\Model\Enum\TaskType;
use App\Common\Model\TaskCollectionInterface;
use App\Converter\Type\AccidentConverter;
use App\Converter\Type\InspectionConverter;
use App\Reader\Model\InputTask;
use App\Separator\Validator\Validator;
use App\Tool\Counter;
use App\Tool\Logger;
use App\View\View;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MainCommand extends Command
{
    private const ARG_INPUT_FILE = 'inputFile';

    private const ERRORS = 'errors.json';

    private const TARGETS = [
        TaskType::INSPECTION => 'inspections.json',
        TaskType::ACCIDENT => 'accidents.json',
    ];

    private const TASK_CONVERTERS = [
        TaskType::INSPECTION => InspectionConverter::class,
        TaskType::ACCIDENT => AccidentConverter::class,
    ];

    protected static $defaultName = 'task_separator';

    protected function configure(): void
    {
        $this
            ->setDescription(
                "Task separator.\n".
                "  App separates tasks from the input JSON file to the output files:\n".
                "  - inspections.json - inspection tasks\n".
                "  - accidents.json   - accident tasks\n".
                "  - errors.json      - tasks with incorrect data"
            )
            ->addArgument(self::ARG_INPUT_FILE, InputArgument::REQUIRED, 'Input JSON file with the list of tasks')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $view = new View(new SymfonyStyle($input, $output));
        $view->showHeader($this->getApplication()->getName(), $this->getApplication()->getVersion());

        if ($inputFile = $input->getArgument(self::ARG_INPUT_FILE)) {
            try {
                $ioType = $this->getFileType($inputFile);
                $validator = new Validator();
                $sourceCollection = $this->getSourceCollection($inputFile, $ioType);
                [$targets, $errors] = $this->separateTasks($sourceCollection, $validator);
                $this->writeTargets($ioType, $targets, $errors);

                $counter = new Counter($targets, $errors);
                $view->showErrors($validator->getErrors());
                $view->showSummary($counter);
                $view->showSuccess();
            } catch (\Throwable $e) {
                $view->showException($e);
                Logger::use()->log($e->getMessage(), ['trace' => $e->getTraceAsString()], Logger::ERROR);
            }
        }

        return Command::SUCCESS;
    }

    private function getFileType(string $filename): string
    {
        if (Format::JSON !== strtolower(pathinfo($filename, PATHINFO_EXTENSION))) {
            throw new \RuntimeException('unknown file type or missing extension. The allowed file format is * .json.');
        }
        return Format::JSON;
    }

    private function getSourceCollection(string $inputFile, string $ioType): TaskCollectionInterface
    {
        $reader = IOFactory::createReader($ioType);
        return $reader->read($inputFile)->getUniqueByCallback(fn (InputTask $item) => trim($item->getDescription()));
    }

    private function separateTasks(TaskCollectionInterface $sourceCollection, Validator $validator): array
    {
        $separator = SeparatorFactory::create(self::TARGETS, self::TASK_CONVERTERS, $validator);
        $separator->process($sourceCollection);

        $targets = $separator->getTargets();
        $errors = $separator->getErrors();

        return [$targets, $errors];
    }

    private function writeTargets(string $ioType, array $targets, TaskCollectionInterface $errors): void
    {
        $writer = IOFactory::createWriter($ioType);
        $writer->write($errors, self::ERRORS);
        foreach (self::TARGETS as $type => $filename) {
            $writer->write($targets[$type], $filename);
        }
    }
}
