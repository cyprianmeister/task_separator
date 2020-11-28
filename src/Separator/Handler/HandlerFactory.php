<?php

namespace App\Separator\Handler;

use App\Converter\Type\ConverterInterface;
use App\Separator\Validator\Validator;

class HandlerFactory
{
    private ConverterInterface $converter;

    private Validator $validator;

    public function __construct(ConverterInterface $converter, Validator $validator)
    {
        $this->converter = $converter;
        $this->validator = $validator;
    }

    public function create(string $className): HandlerInterface
    {
        switch ($className) {
            case ConverterHandler::class:
                return new $className($this->converter);
            case ErrorHandler::class:
                return new $className($this->validator);
        }
    }
}
