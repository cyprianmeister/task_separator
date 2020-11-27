<?php

namespace App\Separator\Handler;

use App\Converter\Type\ConverterInterface;

class HandlerFactory
{
    private ConverterInterface $converter;

    public function __construct(ConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    public function create(string $className): HandlerInterface
    {
        return $className === ConverterHandler::class
            ? new $className($this->converter)
            : new $className();
    }
}
