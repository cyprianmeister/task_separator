<?php

namespace App\Converter;

use App\Converter\Exception\ConverterException;
use App\Converter\Rule\TypeRule;
use App\Converter\Type\ConverterInterface;
use App\Reader\Model\InputTask;
use App\Writer\Model\OutputTask;

final class Converter
{
    private array $strategies;

    public function __construct(array $converterTypes)
    {
        $this->strategies = $converterTypes;
    }

    /**
     * @param InputTask $inputTask
     * @return OutputTask
     * @throws ConverterException
     */
    public function convert(InputTask $inputTask): OutputTask
    {
        $strategy = $this->createStrategy($inputTask);
        return $strategy->convert($inputTask);
    }

    /**
     * @param InputTask $inputTask
     * @return ConverterInterface
     * @throws ConverterException
     */
    private function createStrategy(InputTask $inputTask): ConverterInterface
    {

        $type = (new TypeRule())->convert($inputTask);
        if (!array_key_exists($type, $this->strategies)) {
            throw new ConverterException($type);
        }

        $class = $this->strategies[$type];
        return new $class();
    }
}
