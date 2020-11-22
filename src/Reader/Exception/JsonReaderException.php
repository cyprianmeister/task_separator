<?php

namespace App\Reader\Exception;

use Throwable;

final class JsonReaderException extends \Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        $message = 'JSON content can\'t be read.' . ($message ? ' ' . $message : '');
        parent::__construct($message, $code, $previous);
    }
}
