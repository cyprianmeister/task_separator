<?php

namespace App\Converter\Rule;

use App\Reader\Model\InputTask;

class PhoneRule implements RuleInterface
{
    public function convert(InputTask $inputTask): ?string
    {
        return $inputTask->getPhone();
    }
}
