<?php

namespace App\Tests\Converter\Rule;

use App\Common\Model\Enum\AccidentStatus;
use App\Converter\Rule\AccidentStatusRule;
use App\Reader\Model\InputTask;
use PHPUnit\Framework\TestCase;

class AccidentStatusRuleTest extends TestCase
{
    public function testGettingStatusFromDate(): void
    {
        $statuses = [
            AccidentStatus::NEW => null,
            AccidentStatus::TERM => new \DateTime(),
        ];

        $inputTask = $this->createMock(InputTask::class);
        $rule = new AccidentStatusRule();

        $inputTask->method('getDueDate')->will($this->onConsecutiveCalls(...array_values($statuses)));
        foreach (array_keys($statuses) as $status) {
            $result = $rule->convert($inputTask);
            $this->assertEquals($status, $result);
        }
    }
}
