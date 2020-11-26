<?php

namespace App\Converter\Rule;

use App\Reader\Model\InputTask;

class DescriptionRule implements RuleInterface
{
    public function convert(InputTask $inputTask): ?string
    {
        return $inputTask->getDescription();
    }
}
