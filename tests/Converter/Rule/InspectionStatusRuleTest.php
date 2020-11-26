<?php

namespace App\Tests\Converter\Rule;

use App\Common\Model\Enum\InspectionStatus;
use App\Converter\Rule\InspectionStatusRule;
use App\Reader\Model\InputTask;
use PHPUnit\Framework\TestCase;

class InspectionStatusRuleTest extends TestCase
{
    public function testGettingStatusFromDate(): void
    {
        $statuses = [
            InspectionStatus::NEW => null,
            InspectionStatus::PLANNED => new \DateTime(),
        ];

        $inputTask = $this->createMock(InputTask::class);
        $rule = new InspectionStatusRule();

        $inputTask->method('getDueDate')->will($this->onConsecutiveCalls(...array_values($statuses)));
        foreach (array_keys($statuses) as $status) {
            $result = $rule->convert($inputTask);
            $this->assertEquals($status, $result);
        }
    }
}
