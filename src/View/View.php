<?php

namespace App\View;

use App\Counter;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Style\SymfonyStyle;

class View
{
    private SymfonyStyle $io;

    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
    }

    public function showHeader(string $name, string $version): void
    {
        $this->io->title($name . ' ' . $version);
    }

    public function showSuccess(): void
    {
        $this->io->success('Tasks has been separated');
    }

    public function showException(\Throwable $e): void
    {
        $this->io->error($e->getMessage());
    }

    public function showErrors(array $errors): void
    {
        if ($errors) {
            $this->io->section('Errors');
            $this->io->table(['task number','message'], $this->getErrorList($errors));
        }
    }

    public function showSummary(Counter $counter): void
    {
        $this->io->section('Summary');
        $this->io->table(['task type','qty'], $this->getCounterList($counter));
    }

    private function getErrorList(array $errors): array
    {
        $list = [];
        foreach ($errors as $key => $value) {
            $list[] = [$value['number'], $value['message']];
        }
        return $list;
    }

    private function getCounterList(Counter $counter): array
    {
        $list = [];
        foreach ($counter->getCounters() as $key => $value) {
            $list[] = [$key, $value];
        }
        $list[] = new TableSeparator();
        $list[] = ['all', $counter->getSum()];
        return $list;
    }
}
