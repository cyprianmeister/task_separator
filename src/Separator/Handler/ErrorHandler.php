<?php

namespace App\Separator\Handler;

use App\Reader\Model\InputTask;
use App\Separator\Solution;

class ErrorHandler extends HandlerAbstract
{
    public function handle(?InputTask $inputTask): Solution
    {
        if ($inputTask && !$inputTask->isValid()) {
            return new Solution($inputTask);
        }
        return parent::handle($inputTask);
    }
}
