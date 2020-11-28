<?php

namespace App\Separator\Handler;

use App\Reader\Model\InputTask;
use App\Separator\Solution;
use App\Separator\Validator\Validator;

class ErrorHandler extends HandlerAbstract
{
    private Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function handle(?InputTask $inputTask): Solution
    {
        if ($inputTask && !$this->validator->isValid($inputTask)) {
            return new Solution($inputTask);
        }
        return parent::handle($inputTask);
    }
}
