<?php

namespace App\Writer\Exception;

use Throwable;

final class JsonFileWriterTargetException extends \Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('Target file of JSON file writer is not set.', $code, $previous);
    }
}
