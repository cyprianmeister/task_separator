<?php

namespace App\Tests\Converter\Rule;

use App\Common\Model\Enum\TaskType;
use App\Converter\Rule\TypeRule;
use App\Reader\Model\InputTask;
use PHPUnit\Framework\TestCase;

class TypeRuleTest extends TestCase
{
    public function testGettingTypeFromDescriptionAsAccident1(): void
    {
        $this->runTestCase('Coś się zepsuło.', TaskType::ACCIDENT);
    }

    public function testGettingTypeFromDescriptionAsAccident2(): void
    {
        $this->runTestCase('Proszę o przeglądnięcie i inspekcję.', TaskType::ACCIDENT);
    }

    public function testGettingTypeFromDescriptionAsInspection1(): void
    {
        $this->runTestCase('Proszę o Przglady! instalacji.', TaskType::INSPECTION);
    }

    public function testGettingTypeFromDescriptionAsInspection2(): void
    {
        $this->runTestCase('Proszę o PRZEGLĄD instalacji.', TaskType::INSPECTION);
    }

    public function testGettingTypeFromDescriptionAsInspection3(): void
    {
        $this->runTestCase('Proszę o PrzglaDy instalacji.', TaskType::INSPECTION);
    }

    public function testGettingTypeFromDescriptionAsInspection4(): void
    {
        $this->runTestCase('Czekam od wczoraj,PrzgląD instalacji się jeszcze nie odbył.', TaskType::INSPECTION);
    }

    private function runTestCase(string $description, string $test): void
    {
        $inputTask = $this->createMock(InputTask::class);
        $inputTask->method('getDescription')->willReturn($description);
        $rule = new TypeRule();
        $result = $rule->convert($inputTask);
        $this->assertEquals($test, $result);
    }
}
