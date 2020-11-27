<?php

namespace App\Separator\Handler;

use App\Converter\Type\ConverterInterface;
use App\Reader\Model\InputTask;
use App\Separator\Solution;

class ConverterHandler extends HandlerAbstract
{
    private ConverterInterface $converter;

    public function __construct(ConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    public function handle(?InputTask $inputTask): Solution
    {
        if ($inputTask && $inputTask->isValid()) {
            $outputTask = $this->converter->convert($inputTask);
            return new Solution($outputTask, $outputTask->getType());
        }
        return parent::handle($inputTask);
    }
}
