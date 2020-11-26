<?php

namespace App\Converter\Rule;

use App\Reader\Model\InputTask;

class DueDateRule implements RuleInterface
{
    public function convert(InputTask $inputTask): ?\DateTime
    {
        return $inputTask->getDueDate();
    }
}
