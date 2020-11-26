<?php

namespace App\Converter\Rule;

use App\Common\Model\Enum\AccidentStatus;
use App\Reader\Model\InputTask;

class AccidentStatusRule implements RuleInterface
{
    public function convert(InputTask $inputTask): string
    {
        return $inputTask->getDueDate() ? AccidentStatus::TERM : AccidentStatus::NEW;
    }
}
