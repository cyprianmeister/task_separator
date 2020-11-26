<?php

namespace App\Converter\Type;

use App\Reader\Model\InputTask;
use App\Writer\Model\OutputTask;

interface ConverterInterface
{
    public function convert(InputTask $inputTask): OutputTask;
}
