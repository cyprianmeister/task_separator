<?php

namespace App\Writer\Exception;

use Throwable;

final class JsonFileWriterException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
