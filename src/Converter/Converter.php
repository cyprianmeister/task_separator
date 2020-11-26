<?php

namespace App\Converter;

use App\Common\Model\Enum\TaskType;
use App\Converter\Rule\TypeRule;
use App\Converter\Type\AccidentConverter;
use App\Converter\Type\ConverterInterface;
use App\Converter\Type\InspectionConverter;
use App\Reader\Model\InputTask;
use App\Writer\Model\OutputTask;

class Converter
{
    public function convert(InputTask $inputTask): OutputTask
    {
        $strategy = $this->createStrategy($inputTask);
        return $strategy->convert($inputTask);
    }

    private function createStrategy(InputTask $inputTask): ConverterInterface
    {
        return (new TypeRule())->convert($inputTask) === TaskType::INSPECTION
            ? new InspectionConverter()
            : new AccidentConverter();
    }
}
