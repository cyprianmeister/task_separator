<?php

namespace App\Converter\Exception;

class ConverterException extends \Exception
{
    public function __construct(string $type)
    {
        parent::__construct('Converter type ' . $type . ' doesn\'t exist on list.');
    }
}
