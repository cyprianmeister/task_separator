<?php

namespace App\Converter\Rule;

use App\Reader\Model\InputTask;

interface RuleInterface
{
    /**
     * @param InputTask $inputTask
     * @return mixed
     */
    public function convert(InputTask $inputTask);
}
