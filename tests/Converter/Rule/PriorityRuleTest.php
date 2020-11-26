<?php

namespace App\Tests\Converter\Rule;

use App\Common\Model\Enum\Priority;
use App\Converter\Rule\PriorityRule;
use App\Reader\Model\InputTask;
use PHPUnit\Framework\TestCase;

class PriorityRuleTest extends TestCase
{
    public function testGettingPriorityFromDescription1(): void
    {
        $this->runTestCase('Bardzo pilne zgłoszenie.', Priority::CRITICAL);
    }

    public function testGettingPriorityFromDescription2(): void
    {
        $this->runTestCase('To zgłoszenie jest nie jest bardzo ważne, jest tylko pilne.', Priority::HIGH);
    }

    public function testGettingPriorityFromDescription3(): void
    {
        $this->runTestCase('To jest trochę PiLne.', Priority::HIGH);
    }

    public function testGettingPriorityFromDescription4(): void
    {
        $this->runTestCase('A tutaj jest Brdzo pine z błędami.', Priority::CRITICAL);
    }

    public function testGettingPriorityFromDescription5(): void
    {
        $this->runTestCase('To jest zwykłe zlecenie bardzo nie istotne.', Priority::NORMAL);
    }

    public function testGettingPriorityFromDescription6(): void
    {
        $this->runTestCase('A to jest całkiem zwykłe zlecenie.', Priority::NORMAL);
    }

    public function testGettingPriorityFromDescription7(): void
    {
        $this->runTestCase('A tutaj jest Brdo pine ze zbyt dużymi błędami.', Priority::HIGH);
    }

    private function runTestCase(string $description, string $test): void
    {
        $inputTask = $this->createMock(InputTask::class);
        $inputTask->method('getDescription')->willReturn($description);
        $rule = new PriorityRule();
        $result = $rule->convert($inputTask);
        $this->assertEquals($test, $result);
    }
}
