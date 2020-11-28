<?php

namespace App\Separator\Validator;

class ValidatorException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
