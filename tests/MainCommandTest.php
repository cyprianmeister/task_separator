<?php

namespace App\Tests;

use App\Main;
use App\MainCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class MainCommandTest extends TestCase
{
    public function testCommandExecute(): void
    {
        $command = Main::create()->get(MainCommand::getDefaultName());

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'inputFile' => __DIR__ . '/assets/task_source_real.json',
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Tasks has been separated', $output);
    }
}
