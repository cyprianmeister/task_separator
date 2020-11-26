<?php

namespace App\Converter\Rule;

use App\Common\Model\Enum\InspectionStatus;
use App\Reader\Model\InputTask;

class InspectionStatusRule implements RuleInterface
{
    public function convert(InputTask $inputTask): string
    {
        return $inputTask->getDueDate() ? InspectionStatus::PLANNED : InspectionStatus::NEW;
    }
}
